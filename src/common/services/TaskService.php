<?php

namespace ccheng\task\common\services;

use ccheng\task\common\consts\TaskConst;
use ccheng\task\common\helpers\DateHelpers;
use ccheng\task\common\enums\ErrorEnum;
use ccheng\task\common\helpers\RedisLock;
use ccheng\task\common\models\Task;
use ccheng\task\common\models\forms\TaskCreateForm;
use ccheng\task\common\enums\TaskStatusEnum;
use ccheng\task\common\models\TaskHandler;
use Exception;
use \Yii;

class TaskService
{

    /**
     * 创建 Task
     * @param array $data
     * @return bool|string
     * @throws Exception
     */
    public static function createTask(array $data)
    {
        $taskForm = new TaskCreateForm();
        $taskForm->attributes = $data;
        if ($taskForm->validate()) {
            return $taskForm->save();
        } else {
            throw new Exception(ModelHelper::getModelError($taskForm));
        }
    }

    /**
     * 更新 Task
     * @param Task $task
     * @param array $data
     * @return bool|false|int
     * @throws \Throwable
     */
    public static function updateTask(Task $task, array $data)
    {
        $lock = new RedisLock($task->cc_task_key);
        try {
            if ($lock->repeatLock(TaskConst::TASK_LOCK_TIME, TaskConst::TASK_LOCK_COUNT)) {
                if ($task->cc_task_status == TaskStatusEnum::TASK_STATUS_RUN) {
                    ErrorEnum::throwException(ErrorEnum::TASK_IS_RUNNING);
                }

                if (\Yii::$app->queue->isWaiting($task->cc_task_queue_id)) {
                    \Yii::$app->queue->remove($task->cc_task_queue_id);
                    $task->load($data, '');
                    $task->scenario = Task::SCENARIO_UPDATE;
                    return $task->update();
                } else {
                    ErrorEnum::throwException(ErrorEnum::TASK_IS_RUNNING);
                }
            } else {
                ErrorEnum::throwException(ErrorEnum::TASK_ACTION_LOCK_FAILED);
            }
        } catch (\Exception $e) {
            ErrorEnum::throwException($e->getCode());
        } finally {
            $lock->unlock();
        }
        return false;
    }

    /**
     * 执行 Task
     * @param Task $task
     * @return bool|void
     * @throws Exception
     */
    public static function executeTask(Task $task)
    {
        $lock = new RedisLock($task->cc_task_key);
        try {
            if ($lock->repeatLock(TaskConst::TASK_LOCK_TIME, TaskConst::TASK_LOCK_COUNT)) {
                if ($task->cc_task_status == TaskStatusEnum::TASK_STATUS_RUN) {
                    ErrorEnum::throwException(ErrorEnum::TASK_IS_RUNNING);
                }

                if (\Yii::$app->queue->isWaiting($task->cc_task_queue_id)) {
                    \Yii::$app->queue->remove($task->cc_task_queue_id);
                    return self::process($task);
                } else {
                    ErrorEnum::throwException(ErrorEnum::TASK_IS_RUNNING);
                }
            } else {
                ErrorEnum::throwException(ErrorEnum::TASK_ACTION_LOCK_FAILED);
            }
        } catch (\Exception $e) {
            ErrorEnum::throwException($e->getCode());
        } finally {
            $lock->unlock();
        }
        return false;
    }

    /**
     * 删除 Task
     * @param Task $task
     * @return bool|false|int
     * @throws \Throwable
     */
    public static function deleteTask(Task $task)
    {
        $lock = new RedisLock($task->cc_task_key);
        try {
            if ($lock->repeatLock(TaskConst::TASK_LOCK_TIME, TaskConst::TASK_LOCK_COUNT)) {
                if ($task->cc_task_status == TaskStatusEnum::TASK_STATUS_RUN) {
                    ErrorEnum::throwException(ErrorEnum::TASK_IS_RUNNING);
                }
                if ($task->cc_task_queue_id) {
                    if (\Yii::$app->queue->isWaiting($task->cc_task_queue_id)) {
                        \Yii::$app->queue->remove($task->cc_task_queue_id);
                    } else {
                        ErrorEnum::throwException(ErrorEnum::TASK_IS_RUNNING);
                    }
                }
                return $task->delete();
            } else {
                ErrorEnum::throwException(ErrorEnum::TASK_ACTION_LOCK_FAILED);
            }
        } catch (\Exception $e) {
            ErrorEnum::throwException($e->getCode());
        } finally {
            $lock->unlock();
        }
        return false;

    }

    /**
     * 创建一个新的Task.
     * @param $taskData
     * @param null $task
     * @return bool|Task|null
     */
    public static function saveTask($taskData, $task = null)
    {
        if (!$task) {
            $task = new Task();
        }
        if ($task->isNewRecord) {
            $task->loadDefaultValues();
        }

        $task->load($taskData, '');
        if (!$task->isNewRecord && empty($task->dirtyAttributes)) {
            return $task;
        }
        if ($task->validate()) {
            var_dump($task->cc_task_request_data);
            if ($task->save()) {
                return $task;
            }
        }
        return false;
    }

    public static function findModelByKey($key)
    {
        $model = Task::findOne(['cc_task_key' => $key]);
        if (!$model) {
            $model = new Task();
        }
        return $model;
    }

    public static function findTaskByKey($key)
    {
        return Task::findOne(['cc_task_key' => $key]);
    }


    public static function process(Task $task)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            self::startProcessing($task);

            //1.1:根据Type获取Task Handler.
            $taskHandler = $task->handler;
            if (!isset($taskHandler)) {
                throw new Exception(sprintf("[%s]不能找到对应的Handler", $task->cc_task_type));
            }

            if ($taskHandler->isNeedSuspend($task)) {
                self::suspend($task);
            } else {
                //2:调用Handler 做业务处理
                $result = $taskHandler->process();

                //3:处理成功
                self::processResponse($task, $result);
            }

            $transaction->commit();

        } catch (Exception $ex) {
            $transaction->rollBack();
            try {
                //4:处理失败
                self::processFailed($ex, $task);
            } catch (Exception $ex) {
                Yii::error(sprintf('[%s] Error: %s', DateHelpers::getcurrentDateTime(), $ex->getMessage()), 'task');
                throw $ex;
            }
        }
    }

    public static function startProcessing(Task $task)
    {
        $task->cc_task_status = TaskStatusEnum::TASK_STATUS_RUN;

        $task->cc_task_retry_times += 1;

        if (!$task->save(false)) {
            ErrorEnum::throwException(ErrorEnum::TASK_UPDATE_FAILED);
        }

        $task->refresh();
    }

    public static function suspend(Task $task)
    {
        $task->cc_task_suspend_times += 1;
        $task->cc_task_retry_times = 0;
        $task->cc_task_next_run_time = DateHelpers::getcurrentDateTime(30);
        $task->cc_task_status = TaskStatusEnum::TASK_STATUS_OPEN;
        if (!$task->save()) {
            throw new Exception(json_encode($task->getErrors(), JSON_UNESCAPED_UNICODE));
        }
    }

    public static function processResponse(Task $task, array $result)
    {
        //Code =2 ,任务处理中,还需要等待下次处理.
        if ($result['code'] == ErrorEnum::RESULT_CODE_FAILED) {
            if ($task->handler->isNeedRedo()) {
                $task->cc_task_status = TaskStatusEnum::TASK_STATUS_OPEN;
                $task->cc_task_retry_times += 1;
            } else {
                $task->cc_task_status = TaskStatusEnum::TASK_STATUS_TERMINATED;
            }
            $task->cc_task_queue_id = 0;
            $task->cc_task_next_run_time = self::getNextRunDate($task);
        } else {

            $task->cc_task_response_data = json_encode($result, JSON_UNESCAPED_UNICODE);
            $task->cc_task_status = TaskStatusEnum::TASK_STATUS_CLOSE;

        }

        if (!$task->save()) {
            throw new Exception(json_encode($task->getErrors(), JSON_UNESCAPED_UNICODE));
        }
    }

    public static function processFailed(Exception $ex, Task $task)
    {
        $task->refresh();
        if ($task->handler->isNeedRedo()) {
            $task->cc_task_status = TaskStatusEnum::TASK_STATUS_ERROR;
            $task->cc_task_retry_times += 1;
        } else {
            $task->cc_task_status = TaskStatusEnum::TASK_STATUS_TERMINATED;
        }
        $task->cc_task_queue_id = 0;
        $task->cc_task_execute_log = sprintf("====%s====", $task->cc_task_retry_times)
            . $ex->getMessage() . $ex->getTraceAsString();

        $task->cc_task_next_run_time = self::getNextRunDate($task);

        if (!$task->save()) {
            throw new Exception(json_encode($task->getErrors(), JSON_UNESCAPED_UNICODE));
        }
    }

    public static function getNextRunDate(Task $task)
    {
        $retry_time = $task->cc_task_retry_times;
        $time = time();
        switch ($retry_time) {
            case 1:
                $retry_time = $time + 5 * 60;
            case 2:
                $retry_time = $time + 10 * 60;
            case 3:
                $retry_time = $time + 15 * 60;
            case 4:
                $retry_time = $time + 20 * 60;
        }
        if ($task->cc_task_abort_time >= $retry_time) {
            return $retry_time;
        } else if ($task->cc_task_abort_time >= $time) {
            return $time;
        }
    }

    /**
     * 捞取过期10秒至一分钟内且未过期待入队的任务
     * @return \yii\db\ActiveQuery
     */
    public static function getWaitAsyncTaskQuery()
    {
        $query = Task::find();
        $query->where([
            'cc_task_status' => [
                TaskStatusEnum::TASK_STATUS_OPEN,
                TaskStatusEnum::TASK_STATUS_ERROR
            ],
            'cc_task_queue_id' => 0
        ]);
        $time = time();
        $query->andWhere(['BETWEEN', 'cc_task_next_run_time', $time - 5, $time + 60]);
        $query->andWhere(['>', 'cc_task_abort_time', $time]);
        $query->orderBy(['cc_task_next_run_time' => SORT_ASC, 'cc_task_priority' => SORT_DESC]);
        return $query;
    }

    public static function getTaskTypeMap()
    {
        $query = TaskHandler::find();
        $query->select(['cc_task_handler_desc', 'cc_task_handler_type']);
        $query->indexBy('cc_task_handler_type');
        return $query->column();
    }

}
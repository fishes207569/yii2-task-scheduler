<?php

namespace ccheng\task\console\controllers;

use ccheng\task\common\consts\TaskConst;
use ccheng\task\common\helpers\RedisLock;
use ccheng\task\common\models\Task;
use ccheng\task\common\services\TaskService;
use ccheng\task\common\queue\TaskJob;
use common\helpers\ModelHelper;
use yii\console\Controller;
use yii\db\Exception;
use yii\helpers\Console;

class TaskQueueController extends Controller
{
    public $time;

    public function init()
    {
        $this->time = date('Y-m-d H:i:s');
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * 将执行时间在1分钟内的任务插入队列
     */
    public function actionScheduler()
    {
        Console::output($this->time . ' 开始捞取任务....');
        $lock = new RedisLock('task_to_queue:' . __FUNCTION__);
        try {
            if ($lock->repeatLock(60, TaskConst::TASK_LOCK_COUNT)) {

                $tasks = TaskService::getWaitAsyncTaskQuery();
                Console::output($this->time . ' 捞取任务 ' . $tasks->count() . ' 条');
                foreach ($tasks->each() as $task) {
                    /** @var Task $task */
                    $delay = time() - $task->cc_task_next_run_time;
                    $delay = $delay > 0 ? $delay : 1;
                    $message_id = \Yii::$app->queue->delay($delay)->push(new TaskJob([
                        'task_id' => $task->cc_task_id,
                    ]));
                    $task->cc_task_queue_id = $message_id;
                    if ($task->save()) {
                        Console::output($this->time . ' 任务 task_id:' . $task->cc_task_id . ' 入队成功，待' . time() - $task->cc_task_next_run_time . '后执行');
                    } else {
                        throw new \Exception(ModelHelper::getModelError($task));
                    }
                }

            } else {
                Console::output($this->time . ' 任务入队加锁失败');
            }
        } catch (\Exception $e) {
            Console::output($this->time . ' 任务入队错误：' . $e->getMessage());
        } finally {
            $lock->unlock();
        }
        Console::output($this->time . ' 结束捞取任务....');
    }

    /**
     * 执行任务
     * @param $task_id
     */
    public function actionExec($task_id)
    {
        try {
            $task = Task::findOne(['cc_task_id' => $task_id]);
            TaskService::process($task);
        } catch (\Exception $e) {
            var_dump($e);
        }

    }
}
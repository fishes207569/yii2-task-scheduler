<?php
namespace ccheng\task\common\helpers;

use common\business\payment\core\Pay;
use common\helpers\Dh;
use common\models\Keyvalue;
use common\models\Task;
use common\models\Tasklog;
use common\models\WithholdResult;
use Exception;
use Yii;

class TaskHelper
{
    /**
     * 处理Task
     *
     * @param Task $task
     */
    public static function processTask(Task $task)
    {
        //1:开始处理
        try {
            $task->startProcessing();
        } catch (Exception $ex) {
            Yii::error(sprintf("Task[%s]处理失败,异常信息: %s", $task->task_id, $ex->getMessage()), 'task');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            //1.1:根据Type获取Task Handler.
            $taskHandler = self::getTaskHandler($task);
            if (!isset($taskHandler)) {
                throw new Exception(sprintf("[%s]不能找到对应的Handler", $task->task_type));
            }

            if ($taskHandler->isNeedSuspend($task)) {
                $task->suspend();
            } else {
                //2:调用Handler 做业务处理
                $result = $taskHandler->process($task);

                //3:处理成功
                $task->processSuccess($result);
            }

            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            try {
                //4:处理失败
                $task->processFailed($ex);
            } catch (Exception $ex) {
                Yii::error(sprintf('[%s] Error: %s', Dh::getcurrentDateTime(), $ex->getMessage()), 'task');
            }
        }
    }

    /**
     * 根据运行次数获取下次运行时间
     *
     * @param $runTimes
     *
     * @return string|boolean
     */
    /**
     * @param integer $runTimes
     * @param integer $lowLevelRunTimes
     * @param integer $highLevelRunTimes
     * @param integer $totalRunTimes
     *
     * @return bool|string
     */
    public static function getNextRunDate($runTimes, $lowLevelRunTimes, $highLevelRunTimes, $totalRunTimes)
    {
        if ($runTimes >= $totalRunTimes) {
            return false;
        } else {
            if ($runTimes >= $highLevelRunTimes) {
                return Dh::getcurrentDateTime(($runTimes - $highLevelRunTimes + 1) * 15);
            } elseif ($runTimes >= $lowLevelRunTimes) {
                return Dh::getcurrentDateTime(5);
            } else {
                return Dh::getcurrentDateTime(2);
            }
        }
    }

    /**
     * 根据时间比较获取下次运行时间
     *
     * @param string $compareTime
     * @param int    $maxHours
     * @param int    $addHours
     * @param int    $addMinutes
     *
     * @return \DateTime|string
     */
    public static function getNextRunTime($compareTime, $maxHours = 24, $addHours = 1, $addMinutes = 2)
    {
        $nowDateTime = Dh::getcurrentDateTime();
        $hours       = Dh::calcHours($nowDateTime, $compareTime);
        if ($hours >= $maxHours) {
            $nextRunTime = Dh::calcDateFromAddDate($nowDateTime, $addHours, 'H', Dh::DATE_OPERATOR_ADD, 'Y-m-d H:i:s');
        } else {
            $nextRunTime = Dh::getcurrentDateTime($addMinutes);
        }

        return $nextRunTime;
    }

    /**
     * 下次代扣查询时间 2小时以后
     *
     * @return false|string
     */
    public static function getNextWithholdTime($serial_no)
    {
        $serial_no = trim($serial_no);
        $task      = Task::findOne([
            "task_key"  => Pay::KEY_HEAD_EXECUTE . $serial_no,
            "task_type" => ['Withholding', 'WithholdPaying'],
        ]);
        if (empty($task)) {
            $task = Tasklog::findOne([
                'tasklog_key'  => Pay::KEY_HEAD_EXECUTE . $serial_no,
                'tasklog_type' => ['Withholding', 'WithholdPaying'],
            ]);
            if (empty($task)) {
                $runTimes = 1;
            } else {
                $runTimes = $task->tasklog_retry_times;
            }
        } else {
            $runTimes = $task->task_retry_times;
        }
        if ($runTimes <= 5) {
            $interval = 10;
        } else {
            $interval = 60;
        }

        return Dh::getcurrentDateTime($interval);
    }

    /**
     * 根据运行次数获取下次运行时间
     *
     * @param $runTimes
     *
     * @return string|boolean
     */
    public static function getNextRunTimeAfterHours($runTimes, $totalRunTimes, $hours = 12)
    {
        if ($runTimes >= $totalRunTimes) {
            return false;
        } else {
            return Dh::getcurrentDateTime(60 * $hours + 10);
        }
    }

    /**
     * 根据Task Type 获取异步任务Handler
     *
     * @param Task $task
     *
     * @return null|object
     */
    private static function getTaskHandler(Task $task)
    {
        if (array_key_exists(trim($task->task_type), Yii::$app->params['taskHandlers'])) {
            $taskHandlerName = Yii::$app->params['taskHandlers'][trim($task->task_type)];

            return Yii::createObject($taskHandlerName);
        } else {
            return null;
        }
    }

    /**
     * Masks send qbus msg to msgSvr
     *
     * @param $exception Object
     *
     * @throws \Exception
     */
    public static function sendExceptionToMsgSvr($exception)
    {
        $key    = 'biz_chat_config';
        $result = 0;
        try {
            $keyValue = Keyvalue::getValue($key);
            if ($keyValue->is_enable != 1) {
                return $result;
            }
            $filterRule = $keyValue->ignore_errors;
            $ExMessage  = $exception->getMessage();
            if ($filterRule) {
                foreach ($filterRule as $rule) {
                    if (substr_count($ExMessage, $rule)) {
                        $ExMessage = '';
                        break;
                    }
                }
            }
            if ($ExMessage) {
                static::sendExceptionMsgToQbus($exception);
                $result = 1;
            }
        } catch (\Exception $e) {
            Yii::error('向 Qbus 发送消息失败：' . $e->getMessage(), 'qbus');
            //throw new \Exception('向 Qbus 发送消息失败：' . $e->getMessage());
        }

        return $result;
    }

    /**
     * @param $exception
     *
     * @return mixed
     */
    public static function sendExceptionMsgToQbus($exception)
    {
        $errors            = [];
        $errors['message'] = $exception->getMessage();
        $trace             = array_slice(explode(PHP_EOL, $exception->getTraceAsString()), 0, 5, true);
        $errors['trace']   = implode(PHP_EOL, $trace);

        $topicName = Yii::$app->params['errorHandler.qbus.topicName'];
        $tagName   = Yii::$app->params['errorHandler.qbus.tagName'];

        return Yii::$app->qbus->publishMessage($topicName, [$tagName], $errors);
    }
}

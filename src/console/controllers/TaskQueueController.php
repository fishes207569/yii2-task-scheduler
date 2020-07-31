<?php

namespace ccheng\task\console\controllers;

use ccheng\task\common\models\Task;
use ccheng\task\common\services\TaskService;
use ccheng\task\common\queue\TaskJob;
use common\helpers\ModelHelper;
use yii\console\Controller;
use yii\db\Exception;
use yii\helpers\Console;

class TaskQueueController extends Controller
{
    /**
     * 将执行时间在1分钟内的任务插入队列
     */
    public function actionScheduler()
    {
        Console::output('开始捞取任务....');
        $tasks = TaskService::getWaitAsyncTaskQuery();
        Console::output('捞取任务 ' . $tasks->count() . ' 条');
        foreach ($tasks->each() as $task) {
            /** @var Task $task */
            $message_id = \Yii::$app->queue->delay(time() - $task->cc_task_next_run_time)->push(new TaskJob([
                'task_id' => $task->id,
            ]));
            $task->cc_task_queue_id = $message_id;
            if ($task->save()) {
                Console::output('任务 task_id:' . $task->cc_task_id . ' 入队成功，待' . time() - $task->cc_task_next_run_time . '后执行');
            } else {
                throw new \Exception(ModelHelper::getModelError($task));
            }
        }
        Console::output('结束捞取任务....');
    }
}
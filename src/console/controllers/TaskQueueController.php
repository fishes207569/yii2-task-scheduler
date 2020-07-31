<?php

namespace ccheng\task\console\controllers;

use ccheng\task\common\models\Task;
use ccheng\task\common\services\TaskService;
use ccheng\task\common\queue\TaskJob;
use yii\console\Controller;

class TaskQueueController extends Controller
{
    public function actionScheduler()
    {
        $tasks = TaskService::getWaitAsyncTaskQuery();
        foreach ($tasks->each() as $task) {
            /** @var Task $task */
            $message_id = \Yii::$app->queue->delay(time() - $task->cc_task_next_run_time)->push(new TaskJob([
                'task_id' => $task->id,
            ]));
            $task->cc_task_queue_id = $message_id;
            $task->save();
        }
    }
}
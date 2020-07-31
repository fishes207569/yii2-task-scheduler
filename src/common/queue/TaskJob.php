<?php

namespace ccheng\task\common\queue;

use ccheng\task\common\models\Task;
use ccheng\task\common\services\TaskService;
use yii\base\BaseObject;

class TaskJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 任务ID
     *
     * @var
     */
    public $task_id;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        if ($task = Task::findOne($this->task_id)) {
            TaskService::process($task);
        }

    }
}
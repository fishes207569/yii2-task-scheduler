<?php

namespace ccheng\task\common\behaviors;

use ccheng\task\common\consts\CurdConst;
use ccheng\task\common\models\Task;
use ccheng\task\common\models\TaskCurdLog;
use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

class TaskBehavior extends Behavior
{

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete'
        ];
    }

    public function afterInsert(AfterSaveEvent $event)
    {
        $task = $event->sender;
        /** @var Task $task */
        $task->taskHandler->updateCounters(['cc_task_handler_count' => 1]);
        $log = new TaskCurdLog();
        $log->cc_task_crud_log_task_id = $task->cc_task_id;
        $log->cc_task_crud_log_type = CurdConst::CURD_INSERT;
        $log->cc_task_crud_log_new_value = json_encode($task->attributes, JSON_UNESCAPED_UNICODE);
        return $log->save();
    }

    public function beforeUpdate(ModelEvent $event)
    {
        $task = $event->sender;
        /** @var Task $task */
        $log = new TaskCurdLog();
        $log->cc_task_crud_log_task_id = $task->cc_task_id;
        $log->cc_task_crud_log_type = CurdConst::CURD_UPDATE;
        $log->cc_task_crud_log_new_value = json_encode($task->attributes, JSON_UNESCAPED_UNICODE);
        $log->cc_task_crud_log_old_value = json_encode($task->oldAttributes, JSON_UNESCAPED_UNICODE);
        $event->isValid = $log->save();
    }

    public function beforeDelete(ModelEvent $event)
    {
        $task = $event->sender;
        /** @var Task $task */
        $log = new TaskCurdLog();
        $log->cc_task_crud_log_task_id = $task->cc_task_id;
        $log->cc_task_crud_log_type = CurdConst::CURD_DELETE;
        $log->cc_task_crud_log_old_value = json_encode($task->attributes, JSON_UNESCAPED_UNICODE);
        $event->isValid = $log->save();
    }

}

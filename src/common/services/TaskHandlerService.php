<?php

namespace ccheng\task\common\services;

use ccheng\task\common\enums\StatusEnum;
use ccheng\task\common\models\TaskHandler;
use Exception;
use \Yii;

class TaskHandlerService
{
    public static function getMap($status = StatusEnum::STATUS_ENABLE)
    {
        return TaskHandler::find()
            ->select(['label' => 'cc_task_handler_desc','value' => 'cc_task_handler_type',])
            ->indexBy('value')
            ->where(['cc_task_handler_status' => $status])
            ->column();
    }
}
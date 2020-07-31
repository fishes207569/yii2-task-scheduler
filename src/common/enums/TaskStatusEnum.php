<?php

namespace ccheng\task\common\enums;

use ccheng\task\common\abstracts\Enum;

/**
 * Class TaskStatusEnum
 * @package common\enums
 */
class TaskStatusEnum extends Enum
{
    const TASK_STATUS_OPEN = 'open';
    const TASK_STATUS_RUN = 'run';
    const TASK_STATUS_CLOSE = 'close';
    const TASK_STATUS_ERROR = 'error';
    const TASK_STATUS_TERMINATED = 'terminated';

    public static function getMap(): array
    {
        return [
            static::TASK_STATUS_OPEN => '待处理',
            static::TASK_STATUS_RUN => '处理中',
            static::TASK_STATUS_CLOSE => '成功',
            static::TASK_STATUS_ERROR => '失败',
            static::TASK_STATUS_TERMINATED => '终止',
        ];
    }
}
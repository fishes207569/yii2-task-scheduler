<?php

namespace common\enums;

use ccheng\task\common\abstracts\Enum;

/**
 * Class TaskTypeEnum
 * @package common\enums
 */
class TaskTypeEnum extends Enum
{
    const TASK_TYPE_SYNC = 1;
    const TASK_TYPE_ASYNC = 0;


    public static function getMap(): array
    {
        return [
            static::TASK_TYPE_ASYNC => '异步',
            static::TASK_TYPE_SYNC => '同步'
        ];
    }
}
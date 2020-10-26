<?php

namespace ccheng\task\common\enums;

use ccheng\task\common\abstracts\Enum;

/**
 * Class StatusEnum
 * @package common\enums
 */
class StatusEnum extends Enum
{
    const STATUS_DISABLED = 'disabled';
    const STATUS_ENABLE = 'enabled';

    public static function getMap(): array
    {
        return [
            static::STATUS_DISABLED => '禁用',
            static::STATUS_ENABLE => '启用',
        ];
    }
}
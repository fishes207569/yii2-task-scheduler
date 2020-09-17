<?php

namespace ccheng\task\common\enums;

use ccheng\task\common\abstracts\Enum;

/**
 * Class SystemEnum
 * @package common\enums
 */
class SystemEnum extends Enum
{
    const SYSTEM_MEDIA = 'media';

    public static function getMap(): array
    {
        return [
            static::SYSTEM_MEDIA => '数媒平台'
        ];
    }
}
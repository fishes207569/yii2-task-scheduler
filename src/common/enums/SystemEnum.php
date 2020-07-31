<?php

namespace ccheng\task\common\enums;

use ccheng\task\common\abstracts\Enum;

/**
 * Class SystemEnum
 * @package common\enums
 */
class SystemEnum extends Enum
{
    const SYSTEM_VIAUDIO = 'viaudio';

    public static function getMap(): array
    {
        return [
            static::SYSTEM_VIAUDIO => 'CMS'
        ];
    }
}
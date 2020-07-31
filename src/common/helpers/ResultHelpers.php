<?php

namespace ccheng\task\common\helpers;

use ccheng\task\common\enums\ErrorEnum;

class ResultHelpers
{
    public static function success($data = [])
    {
        return [
            'code' => ErrorEnum::RESULT_CODE_SUCCESS,
            'message' => 'ok',
            'data' => $data
        ];
    }

    public static function failed($code)
    {
        return [
            'code' => $code,
            'message' => ErrorEnum::getValue($code),
            'data' => []
        ];
    }
}
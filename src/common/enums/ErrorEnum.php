<?php

namespace ccheng\task\common\enums;

use ccheng\task\common\abstracts\Enum;
use Exception;

class ErrorEnum extends Enum
{

    const  RESULT_CODE_SUCCESS = 0;
    const  RESULT_CODE_FAILED = 1;
    const ERROR_CODE_SYSTEM_ERROR = 10000;
    const TASK_UNDEFINED_OR_DISABLED = 20001;
    const TASK_EXEC_TIME_INVALID = 20002;
    const TASK_IS_MULTIPLE = 20003;
    const TASK_IS_CLOSED = 20004;
    const TASK_NOT_EXISTS = 20005;
    const TASK_IS_RUNNING = 20006;
    const TASK_TIME_ERROR = 20007;
    const TASK_SAVE_FAILED = 20008;
    const TASK_EXEC_FAILED = 20009;
    const TASK_EXEC_SUCCESS = 20011;
    const TASK_ACTION_FAILED = 20010;
    const TASK_ABORT_TIME_ERROR = 20012;
    const TASK_UPDATE_FAILED = 20013;
    const TASK_EXISTS = 20014;
    const TASK_ACTION_LOCK_FAILED = 20015;
    const TASK_ABORT_TIME_INVALID = 20016;
    const TASK_EXEC_TIME_ERROR = 20017;

    public static function getMap(): array
    {
        return [
            self::RESULT_CODE_SUCCESS => '成功',
            self::RESULT_CODE_FAILED => '失败',
            self::ERROR_CODE_SYSTEM_ERROR => '系统错误',
            self::TASK_UNDEFINED_OR_DISABLED => '任务处理器未定义或启用',
            self::TASK_EXEC_TIME_INVALID => '任务执行时间已过期或必须过短',
            self::TASK_IS_MULTIPLE => '任务不具备唯一性，无法修改',
            self::TASK_IS_CLOSED => '任务已关闭',
            self::TASK_NOT_EXISTS => '任务不存在',
            self::TASK_IS_RUNNING => '任务执行中',
            self::TASK_TIME_ERROR => '任务执行时间格式错误',
            self::TASK_SAVE_FAILED => '任务保存失败',
            self::TASK_EXEC_FAILED => '任务执行失败',
            self::TASK_EXEC_SUCCESS => '任务执行成功',
            self::TASK_ACTION_FAILED => '任务操作失败',
            self::TASK_ABORT_TIME_ERROR => '任务执行时间间隔太短',
            self::TASK_UPDATE_FAILED => '任务更新失败',
            self::TASK_EXISTS => '任务已存在',
            self::TASK_ACTION_LOCK_FAILED => '任务操作加锁失败',
            self::TASK_ABORT_TIME_INVALID => '任务执行截止时间已过期',
            self::TASK_EXEC_TIME_ERROR => '任务执行截止时间间隔太短',
        ];
    }

    /**
     * 抛出异常
     * @param $code
     * @throws Exception
     */
    public static function throwException($code)
    {
        throw new Exception(self::getValue($code), $code);
    }
}
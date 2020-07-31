<?php

namespace ccheng\task\common\consts;
/**
 * Class TaskConst
 * @package ccheng\task\common\consts
 */
class TaskConst
{
    //默认优先级
    const PRIORITY_1 = 1;

    //最大重试次数
    const MAX_REDO_NUM = 5;

    //距离当前最短运行时间（秒）
    const MIN_RUN_SEC = 60;

    //任务默认推迟时间（秒）
    const DEFAULT_RUN_SEC = 60;

    //距离运行时间最迟的截止时间（秒）
    const MIN_ABORT_SEC = 60;

    //任务加锁时间
    const TASK_LOCK_TIME = 600;

    //尝试加锁次数
    const TASK_LOCK_COUNT = 3;

}
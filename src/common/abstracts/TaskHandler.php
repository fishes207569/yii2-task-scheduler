<?php

namespace ccheng\task\common\abstracts;


use ccheng\task\common\consts\TaskConst;
use ccheng\task\common\interfaces\TaskHandlerInterface;
use ccheng\task\common\models\Task;

/**
 * Class TaskHandler
 * @package ccheng\task\common\abstracts
 */
abstract class TaskHandler implements TaskHandlerInterface
{

    /** @var Task */
    public $task;

    /** @var array */
    public $params;

    /**
     * 任务处理
     * @return mixed
     */
    private function process()
    {
        return $this->execute();
    }


    public function isNeedSuspend(): bool
    {
        return false;
    }

    public function isNeedRedo(): bool
    {
        if ($this->task->cc_task_retry_times < TaskConst::MAX_REDO_NUM && $this->task->cc_task_abort_time >= time()) {
            return true;
        }
        return false;
    }

    public function __call($name, $arguments)
    {
        if ($name == 'process') {
            if($this->validateAndPreprocess()){
                return $this->process();
            }
        }
    }

}
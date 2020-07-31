<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/6/3
 * Time: 下午4:26
 */

namespace ccheng\task\common\interfaces;

interface TaskHandlerInterface
{

    /**
     * 任务处理
     * @return mixed
     */
    public function execute();

    /**
     * 是否需要重试
     * @return bool
     */
    public function isNeedRedo(): bool;

    /**
     * 任务是否需要挂起
     * @return mixed
     */
    public function isNeedSuspend(): bool;

    /**
     * 任务校验/预处理
     * @return mixed
     */
    public function validateAndPreprocess() ;

}

<?php

namespace ccheng\task\example;

use ccheng\task\common\abstracts\TaskHandler;
use ccheng\task\common\enums\ErrorEnum;
use yii\base\UserException;

class DemoTaskHandler extends TaskHandler
{
    public function execute()
    {
        return ['code' => ErrorEnum::RESULT_CODE_SUCCESS, 'message' => 'ok', 'data' => ['order_no' => 'ds35423423']];
    }

    public function validateAndPreprocess()
    {
        $this->params = $this->task->cc_task_request_data;
        if (isset($this->params['aa']) && $this->params['aa'] < 100) {
            throw new UserException('金额异常');
        }
        return true;
    }

}
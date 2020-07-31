<?php

namespace ccheng\task\common\models\forms;

use ccheng\task\common\consts\TaskConst;
use ccheng\task\common\enums\SystemEnum;
use ccheng\task\common\helpers\ResultHelpers;
use ccheng\task\common\models\TaskHandler;
use ccheng\task\common\traits\ValidateTrait;
use ccheng\task\common\enums\ErrorEnum;
use common\enums\TaskStatusEnum;
use Exception;
use yii\base\Model;
use ccheng\task\common\services\TaskService;

/**
 * Class TaskCreateForm
 * @package ccheng\task\common\models\forms
 * @property $request_data
 * @property $type
 * @property $key
 * @property $form_source
 * @property $run_time
 * @property $priority
 * @property $abort_time
 */
class TaskCreateForm extends Model
{
    use ValidateTrait;

    /**
     * 任务参数
     * @var string $request_data
     */
    public $request_data = [];

    /**
     * 任务类型
     * @var string
     */
    public $type;

    /**
     * 任务唯一键
     * @var string
     */
    public $key;

    public $form_source = SystemEnum::SYSTEM_VIAUDIO;

    /**
     * 运行时间
     * @var
     */
    public $run_time;

    public $priority = TaskConst::PRIORITY_1;

    /**
     * 最后截止时间
     * @var int
     */
    public $abort_time = 0;

    /** @var TaskHandler */
    protected $taskHandler;

    public function rules()
    {
        return [
            ['type', 'key', 'required'],
            ['request_data', 'validateJson'],
            ['type', 'checkType'],
            ['priority', 'integer'],
            [['key'], 'string'],
            ['run_time', 'default', 'value' => function () {

                return strtotime('+' . TaskConst::DEFAULT_RUN_SEC . ' sec');

            }],
            ['run_time', 'default', 'value' => function () {

                return strtotime('+' . TaskConst::MIN_ABORT_SEC . ' sec', $this->run_time);

            }],
            ['run_time', 'validateTime'],
            ['abort_time', 'validateAbortTime']
        ];
    }

    public function checkType($attribute, $params)
    {
        $taskHandler = TaskHandler::findOne(['cc_task_handler_type' => $this->type, 'cc_task_handler_status' => TaskStatusEnum::TASK_STATUS_OPEN]);
        if ($taskHandler) {
            $this->taskHandler = $taskHandler;
            return true;
        } else {
            return $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_UNDEFINED_OR_DISABLED));
        }
    }

    public function validateTime($attribute, $params)
    {

        if ($this->$attribute) {
            if (date('Y-m-d H:i:s', strtotime($this->$attribute)) != $this->$attribute) {
                return $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_TIME_ERROR));
            }
            if (strtotime($this->$attribute) <= strtotime('+5 sec')) {
                return $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_EXEC_TIME_INVALID));
            }
        }

        return true;
    }

    public function validateAbortTime($attribute, $params)
    {
        if (strtotime($this->run_time) <= strtotime('+1 min', $this->$attribute)) {
            return $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_UNDEFINED_OR_DISABLED));
        }
        return true;
    }

    public function save()
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $task = null;
            $task = TaskService::findModelByKey($this->key);
            if (!$task->isNewRecord) {
                if ($task->cc_task_status == TaskStatusEnum::TASK_STATUS_CLOSE) {
                    return $task->cc_task_response_data;
                }
                ErrorEnum::throwException(ErrorEnum::TASK_EXISTS);
            }
            $data = [];
            $data['cc_task_type'] = $this->type;
            $data['cc_task_from_system'] = $this->form_source;
            $data['cc_task_key'] = $this->key;
            $data['cc_task_next_run_time'] = $this->run_time;
            $data['cc_task_request_data'] = $this->request_data;
            $data['cc_task_priority'] = $this->priority;
            $data['cc_task_abort_time'] = $this->abort_time;

            $task = TaskService::saveTask($data, $task);
            if (!$task) {
                ErrorEnum::throwException(ErrorEnum::TASK_SAVE_FAILED);
            }
            $transaction->commit();

            return ResultHelpers::success();

        } catch (Exception $ex) {

            $transaction->rollBack();

            return ResultHelpers::failed($ex->getCode());
        }
    }


}

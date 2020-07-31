<?php

namespace ccheng\task\common\models;

use ccheng\task\common\abstracts\TaskHandler;
use ccheng\task\common\consts\TaskConst;
use ccheng\task\common\enums\ErrorEnum;
use ccheng\task\common\enums\SystemEnum;
use common\enums\TaskStatusEnum;
use ccheng\task\common\interfaces\TaskHandlerInterface;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property integer $cc_task_id
 * @property string $cc_task_type
 * @property string $cc_task_key
 * @property string $cc_task_from_system
 * @property string $cc_task_request_data
 * @property string $cc_task_response_data
 * @property string $cc_task_execute_log
 * @property integer $cc_task_is_sync
 * @property string $cc_task_status
 * @property integer $cc_task_next_run_time
 * @property integer $cc_task_retry_times
 * @property integer $cc_task_suspend_times
 * @property integer $cc_task_priority
 * @property string $cc_task_create_at
 * @property string $cc_task_queue_id
 * @property string $cc_task_update_at
 * @property string $cc_task_abort_time
 * @property \ccheng\task\common\models\TaskHandler $taskHandler
 * @property TaskHandlerInterface | TaskHandler $handler
 * @property array $responseData
 * @property array $requestData
 */
class Task extends ActiveRecord
{
    const SCENARIO_INSERT = 'insert';

    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cc_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_task_type', 'cc_task_from_system', 'cc_task_key', 'cc_task_request_data', 'cc_task_priority'], 'required'],
            [['cc_task_retry_times', 'cc_task_id', 'cc_task_priority', 'cc_task_abort_time'], 'integer'],
            [['cc_task_status', 'cc_task_next_run_time', 'cc_task_create_at'], 'string'],
            ['cc_task_from_system', 'in', 'range' => SystemEnum::getKeys()],
            [['cc_task_request_data', 'cc_task_response_data'], 'validateJson'],
            ['cc_task_status', 'in', 'range' => TaskStatusEnum::getKeys()],
            ['cc_task_next_run_time', 'default', 'value' => function () {
                return time() + TaskConst::DEFAULT_RUN_SEC;
            }, 'on' => self::SCENARIO_INSERT, 'when' => function ($model) {
                return $model->cc_task_status == TaskStatusEnum::TASK_STATUS_OPEN;
            }],
            [['cc_task_next_run_time'], function ($model, $attribute) {
                if ($this->$attribute < time()) {
                    $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_EXEC_TIME_INVALID));
                }
                if ($this->$attribute - time() < TaskConst::MIN_RUN_SEC) {
                    $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_EXEC_TIME_ERROR));
                }
                return true;
            }, 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE], 'when' => function ($model) {
                return $model->cc_task_status == TaskStatusEnum::TASK_STATUS_OPEN;
            }],
            ['cc_task_abort_time', 'default', 'value' => function () {
                return $this->cc_task_next_run_time + TaskConst::MIN_ABORT_SEC;
            }, 'on' => self::SCENARIO_INSERT, 'when' => function ($model) {
                return $model->cc_task_status == TaskStatusEnum::TASK_STATUS_OPEN;
            }],
            ['cc_task_abort_time', function ($model, $attribute) {
                if ($this->$attribute < time()) {
                    $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_ABORT_TIME_INVALID));
                }
                if ($this->$attribute - time() < TaskConst::MIN_ABORT_SEC) {
                    $this->addError($attribute, ErrorEnum::getValue(ErrorEnum::TASK_ABORT_TIME_ERROR));
                }
                return true;
            }, 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE], 'when' => function ($model) {
                return $model->cc_task_status == TaskStatusEnum::TASK_STATUS_OPEN;
            }],

            [['cc_task_execute_log', 'requestData', 'cc_task_is_sync'], 'safe'],
            ['cc_task_key', 'unique'],
            [['cc_task_type', 'cc_task_key', 'cc_task_from_system', 'cc_task_next_run_time',], 'string', 'max' => 50],];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cc_task_id' => '任务编号',
            'cc_task_type' => '任务类型',
            'cc_task_key' => '任务键值',
            'cc_task_from_system' => '任务来源',
            'cc_task_request_data' => '任务内容',
            'cc_task_response_data' => '任务结果',
            'cc_task_execute_log' => '备注内容',
            'cc_task_status' => '任务状态',
            'cc_task_next_run_time' => '下次运行时间',
            'cc_task_retry_times' => '重试次数',
            'cc_task_create_at' => '创建日期',
            'cc_task_update_at' => '最后更新日期',
            'cc_task_priority' => '任务优先级',
            'cc_task_abort_time' => '任务处理截止时间',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['cc_task_create_at', 'cc_task_update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['cc_task_update_at']
                ]
            ]
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
            self::SCENARIO_INSERT => self::OP_INSERT,
            self::SCENARIO_UPDATE => self::OP_UPDATE
        ];
    }

    public function getTaskHandler()
    {
        return $this->hasOne(TaskHandler::class, ['cc_task_handler_type' => 'cc_task_type']);
    }

    public function getCurdLog()
    {
        return $this->hasMany(TaskCurdLog::class, ['cc_task_crud_log_task_id' => 'cc_task_id']);
    }

    public function getHandler()
    {
        return \Yii::createObject([
            'class' => $this->taskHandler->cc_task_handler_class,
            'task' => $this
        ]);
    }

    public function validateJson($attribute, $param)
    {
        json_decode(trim($this->$attribute));
        if (json_last_error() != JSON_ERROR_NONE) {
            $this->addError($attribute, "不是Json格式");
        }
    }

    public function getRequestData()
    {
        return json_decode($this->cc_task_request_data, true);
    }

    public function getResponseData()
    {
        return json_decode($this->cc_task_response_data, true);
    }


}

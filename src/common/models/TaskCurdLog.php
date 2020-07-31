<?php

namespace ccheng\task\common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cc_task_crud_log".
 *
 * @property int $cc_task_crud_log_id
 * @property int $cc_task_crud_log_task_id 任务ID
 * @property string $cc_task_crud_log_type 操作类型
 * @property string $cc_task_crud_log_old_value 旧值
 * @property string $cc_task_crud_log_new_value 新值
 * @property int $cc_task_crud_log_operator 操作人员
 * @property string $cc_task_crud_log_create_at 创建时间
 * @property string $cc_task_crud_log_update_at 更新时间
 */
class TaskCurdLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cc_task_crud_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cc_task_crud_log_task_id', 'cc_task_crud_log_operator'], 'integer'],
            [['cc_task_crud_log_old_value', 'cc_task_crud_log_new_value'], 'string'],
            [['cc_task_crud_log_old_value', 'cc_task_crud_log_new_value'], 'default', 'value' => '[]'],
            [['cc_task_crud_log_create_at', 'cc_task_crud_log_update_at'], 'safe'],
            [['cc_task_crud_log_type'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cc_task_crud_log_id' => 'ID',
            'cc_task_crud_log_task_id' => '任务ID',
            'cc_task_crud_log_type' => '操作类型',
            'cc_task_crud_log_old_value' => '旧值',
            'cc_task_crud_log_new_value' => '新值',
            'cc_task_crud_log_operator' => '操作人员',
            'cc_task_crud_log_create_at' => '创建时间',
            'cc_task_crud_log_update_at' => '更新时间',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['cc_task_crud_log_create_at', 'cc_task_crud_log_update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['cc_task_crud_log_update_at']
                ]
            ],
        ];
    }


}

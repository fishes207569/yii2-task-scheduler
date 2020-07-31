<?php

namespace ccheng\task\common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cc_task_handler".
 *
 * @property int $cc_task_handler_id
 * @property string $cc_task_handler_type 类型
 * @property string $cc_task_handler_class 实现类
 * @property string $cc_task_handler_desc 任务描述
 * @property string $cc_task_handler_from_system 来源系统
 * @property string $cc_task_handler_status 状态
 * @property int $cc_task_handler_count 任务计数
 * @property string $cc_task_handler_create_at 创建时间
 * @property string $cc_task_handler_update_at 更新时间
 */
class TaskHandler extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cc_task_handler';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cc_task_handler_type', 'cc_task_handler_class'], 'required'],
            [['cc_task_handler_count'], 'integer'],
            [['cc_task_handler_create_at', 'cc_task_handler_update_at'], 'safe'],
            [['cc_task_handler_type'], 'string', 'max' => 64],
            [['cc_task_handler_class','cc_task_handler_desc'], 'string', 'max' => 255],
            [['cc_task_handler_from_system', 'cc_task_handler_status'], 'string', 'max' => 16],
            [['cc_task_handler_type'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cc_task_handler_id' => 'Cc Task Handler ID',
            'cc_task_handler_type' => '类型',
            'cc_task_handler_class' => '实现类',
            'cc_task_handler_desc' => '任务描述',
            'cc_task_handler_from_system' => '来源系统',
            'cc_task_handler_status' => '状态',
            'cc_task_handler_count' => '任务计数',
            'cc_task_handler_create_at' => '创建时间',
            'cc_task_handler_update_at' => '更新时间',
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
                ],
            ]
        ];
    }
}

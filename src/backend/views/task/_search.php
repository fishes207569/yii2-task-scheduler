<?php

use kartik\datetime\DateTimePicker;
use yii\web\JsExpression;
use common\widgets\kartik\ActiveForm;
use common\widgets\kartik\Select2;
use common\helpers\Html;
use kartik\date\DatePicker;


/** @var \yii\web\View $this */
/* @var $model \backend\modules\order\forms\PaidOrderForm */
/* @var $formSystemMap array */
/* @var $taskStatusMap array */
/* @var $taskTypeMap array */
?>
<div class="box">
    <div class="box-header with-border">
        <div class="box-title"> 搜索</div>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => [''],
        'method' => 'get',
        'id' => 'form-search',
        'type' => ActiveForm::TYPE_INLINE,
    ]); ?>
    <div class="box-body">
        <?= $form->field($model, 'form_system', ['options' => ['style' => 'min-width:120px']])->widget(Select2::class, ['data' => $formSystemMap, 'options' => ['placeholder' => '来源系统'],
            "hideSearch" => true,
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'size' => Select2::MEDIUM
        ]) ?>


        <?= $form->field($model, 'task_type', ['options' => ['style' => 'min-width:200px']])->widget(Select2::class, ['data' => $taskTypeMap, 'options' => ['placeholder' => '任务类型'],
            "hideSearch" => true,
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'size' => Select2::MEDIUM
        ]) ?>
        <?= $form->field($model, 'task_key',['options' => ['style' => 'min-width:200px']])->textInput()->label('任务键值') ?>

        <?= $form->field($model, 'start_time')->widget(DateTimePicker::class, [
            'language' => 'zh-CN',
            'options' => [
                'value' => $model->start_time ? date('Y-m-d H:i:s', $model->start_time) : date("Y-m-d") . " 00:00:00",
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true,//今日高亮
                'autoclose' => true,//选择后自动关闭
                'todayBtn' => true,//今日按钮显示
            ]
        ]); ?>
        <?= $form->field($model, 'end_time')->widget(DateTimePicker::class, [
            'language' => 'zh-CN',
            'options' => [
                'value' => $model->end_time ? date('Y-m-d H:i:s', $model->end_time) : date("Y-m-d") . " 23:59:59",
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true,//今日高亮
                'autoclose' => true,//选择后自动关闭
                'todayBtn' => true,//今日按钮显示
            ]
        ]); ?>

        <?= $form->field($model, 'task_status', ['options' => ['style' => 'min-width:100px']])->widget(Select2::class, ['data' => $taskStatusMap, 'options' => ['placeholder' => '任务状态'],
            "hideSearch" => true,
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'size' => Select2::MEDIUM
        ]) ?>


        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary mr-1']) ?>
            <?= Html::a('重置', 'index', ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\Task */
/* @var $form yii\widgets\ActiveForm */
/* @var $taskTypeMap array */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cc_task_type')->widget(\kartik\select2\Select2::class, [
        'data' => $taskTypeMap,
        'options' => ['placeholder' => '选择任务类型'],
        'hideSearch' => true,
        'pluginOptions' => [
            'allowClear' => false,
            'tags' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'cc_task_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cc_task_from_system')->widget(\kartik\select2\Select2::class, [
        'data' => \ccheng\task\common\enums\SystemEnum::getMap(),
        'options' => ['placeholder' => '请选择来源系统'],
        'hideSearch' => true,
        'pluginOptions' => [
            'allowClear' => false,
            'tags' => true,
        ],
    ]) ?>
    <?= $form->field($model, 'cc_task_priority')->input('number', ['min' => 1, 'value' => 1])->hint('* 数值越大优先级越高') ?>
    <?= $form->field($model, 'cc_task_request_data')->widget(
        \kdn\yii2\JsonEditor::class,
        [
            'clientOptions' => ['modes' => ['code', 'tree'], 'mode' => 'code'],

        ]
    ); ?>

    <?= $form->field($model, 'cc_task_status')->widget(\kartik\select2\Select2::class, [
        'data' => \ccheng\task\common\enums\TaskStatusEnum::getMap(),
        'options' => ['placeholder' => '选择任务状态'],
        'hideSearch' => true,
        'pluginOptions' => [
            'allowClear' => false,
            'tags' => true,
        ],
    ]) ?>
    <?= $form->field($model, 'cc_task_next_run_time')->widget(kartik\datetime\DateTimePicker::class, [
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->isNewRecord ? date('Y-m-d H:i:s', strtotime('+5 min')) : date('Y-m-d H:i:s', $model->cc_task_next_run_time),
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true, // 今日高亮
            'autoclose' => true, // 选择后自动关闭
            'todayBtn' => true, // 今日按钮显示
        ]
    ]) ?>
    <?= $form->field($model, 'cc_task_abort_time')->widget(kartik\datetime\DateTimePicker::class, [
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->isNewRecord ? date('Y-m-d H:i:s', strtotime('+6 min')) : date('Y-m-d H:i:s', $model->cc_task_abort_time),
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true, // 今日高亮
            'autoclose' => true, // 选择后自动关闭
            'todayBtn' => true, // 今日按钮显示
        ]
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
        <?= Html::a('取消', \Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

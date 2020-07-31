<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cc_task_id')->textInput() ?>

    <?= $form->field($model, 'cc_task_type')->dropDownList([]) ?>

    <?= $form->field($model, 'cc_task_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cc_task_from_system')->dropDownList([]) ?>

    <?= $form->field($model, 'cc_task_request_data')->textarea() ?>

    <?= $form->field($model, 'cc_task_response_data')->textarea() ?>

    <?= $form->field($model, 'cc_task_execute_log')->textarea() ?>

    <?= $form->field($model, 'cc_task_status')->dropDownList([ 'open' => 'Open', 'running' => 'Running', 'error' => 'Error', 'terminated' => 'Terminated', 'close' => 'Close', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'cc_task_next_run_time')->widget(kartik\datetime\DateTimePicker::class, [
                        'language' => 'zh-CN',
                        'options' => [
                            'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->cc_task_next_run_time),
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd hh:ii',
                            'todayHighlight' => true, // 今日高亮
                            'autoclose' => true, // 选择后自动关闭
                            'todayBtn' => true, // 今日按钮显示
                        ]
                    ]) ?>

    <?= $form->field($model, 'cc_task_retry_times')->textInput() ?>

    <?= $form->field($model, 'cc_task_create_at')->textInput() ?>

    <?= $form->field($model, 'cc_task_priority')->textInput() ?>

    <?= $form->field($model, 'cc_task_abort_time')->widget(kartik\datetime\DateTimePicker::class, [
                        'language' => 'zh-CN',
                        'options' => [
                            'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->cc_task_abort_time),
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd hh:ii',
                            'todayHighlight' => true, // 今日高亮
                            'autoclose' => true, // 选择后自动关闭
                            'todayBtn' => true, // 今日按钮显示
                        ]
                    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

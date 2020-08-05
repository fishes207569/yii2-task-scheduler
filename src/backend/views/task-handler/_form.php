<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\TaskHandler */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-handler-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cc_task_handler_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cc_task_handler_class')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cc_task_handler_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cc_task_handler_from_system')->widget(\kartik\select2\Select2::class, [
        'data' => \ccheng\task\common\enums\SystemEnum::getMap(),
        'options' => ['placeholder' => '请选择来源系统'],
        'hideSearch' => true,
        'pluginOptions' => [
            'allowClear' => false,
            'tags' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'cc_task_handler_status')->widget(\kartik\select2\Select2::class, [
        'data' => \ccheng\task\common\enums\StatusEnum::getMap(),
        'options' => ['placeholder' => '处理器状态'],
        'hideSearch' => true,
        'pluginOptions' => [
            'allowClear' => false,
            'tags' => true,
        ],
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
        <?= Html::a('取消', \Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

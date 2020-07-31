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

    <?= $form->field($model, 'cc_task_handler_from_system')->dropDownList([]) ?>

    <?= $form->field($model, 'cc_task_handler_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cc_task_handler_count')->textInput() ?>

    <?= $form->field($model, 'cc_task_handler_create_at')->textInput() ?>

    <?= $form->field($model, 'cc_task_handler_update_at')->textInput() ?>

    <?= $form->field($model, 'cc_task_handler_desc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

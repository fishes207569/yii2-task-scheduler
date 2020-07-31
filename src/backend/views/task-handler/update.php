<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\TaskHandler */

$this->title = 'Update Task Handler: ' . $model->cc_task_handler_id;
$this->params['breadcrumbs'][] = ['label' => 'Task Handlers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cc_task_handler_id, 'url' => ['view', 'id' => $model->cc_task_handler_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="task-handler-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

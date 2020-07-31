<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\Task */

$this->title = 'Update Task: ' . $model->cc_task_id;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cc_task_id, 'url' => ['view', 'id' => $model->cc_task_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

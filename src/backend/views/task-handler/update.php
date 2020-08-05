<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\TaskHandler */

$this->title = '更新处理器' ;
$this->params['breadcrumbs'][] = ['label' => '任务处理器列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cc_task_handler_id, 'url' => ['view', 'id' => $model->cc_task_handler_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="task-handler-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

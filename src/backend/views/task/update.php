<?php

use ccheng\task\common\services\TaskService;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\Task */

$this->title = '更新任务';
$this->params['breadcrumbs'][] = ['label' => '异步任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cc_task_id, 'url' => ['view', 'id' => $model->cc_task_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="task-update">

    <?= $this->render('_form', [
        'model' => $model,
        'taskTypeMap' => $taskTypeMap
    ]) ?>

</div>

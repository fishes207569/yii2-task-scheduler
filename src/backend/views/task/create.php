<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\Task */

$this->title = '添加异步任务';
$this->params['breadcrumbs'][] = ['label' => '异步任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <?= $this->render('_form', [
        'model' => $model,
        'taskTypeMap' => $taskTypeMap
    ]) ?>

</div>

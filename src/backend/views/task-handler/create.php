<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\TaskHandler */

$this->title = '添加处理器';
$this->params['breadcrumbs'][] = ['label' => '任务处理器列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-handler-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

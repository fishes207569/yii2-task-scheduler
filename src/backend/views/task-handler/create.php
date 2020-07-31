<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\TaskHandler */

$this->title = 'Create Task Handler';
$this->params['breadcrumbs'][] = ['label' => 'Task Handlers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-handler-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

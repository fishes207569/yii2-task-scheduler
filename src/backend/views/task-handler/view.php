<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\TaskHandler */

$this->title = $model->cc_task_handler_id;
$this->params['breadcrumbs'][] = ['label' => 'Task Handlers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-handler-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cc_task_handler_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cc_task_handler_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cc_task_handler_id',
            'cc_task_handler_type',
            'cc_task_handler_class',
            'cc_task_handler_from_system',
            'cc_task_handler_status',
            'cc_task_handler_count',
            'cc_task_handler_create_at',
            'cc_task_handler_update_at',
            'cc_task_handler_desc',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\Task */

$this->title = $model->cc_task_id;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cc_task_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cc_task_id], [
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
            'cc_task_id',
            'cc_task_type',
            'cc_task_key',
            'cc_task_from_system',
            'cc_task_request_data',
            'cc_task_response_data',
            'cc_task_execute_log:ntext',
            'cc_task_status',
            'cc_task_next_run_time:datetime',
            'cc_task_retry_times:datetime',
            'cc_task_create_at',
            'cc_task_update_at',
            'cc_task_suspend_times:datetime',
            'cc_task_priority',
            'cc_task_queue_id',
            'cc_task_abort_time:datetime',
        ],
    ]) ?>

</div>

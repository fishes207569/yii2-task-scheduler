<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('添加任务', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cc_task_id',
            'cc_task_type',
            'cc_task_key',
            'cc_task_from_system',
            'cc_task_request_data',
            //'cc_task_response_data',
            //'cc_task_execute_log:ntext',
            'cc_task_status',
            'cc_task_next_run_time:datetime',
            'cc_task_retry_times:datetime',
            'cc_task_create_at',
            //'cc_task_update_at',
            //'cc_task_suspend_times:datetime',
            'cc_task_priority',
            //'cc_task_queue_id',
            'cc_task_abort_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

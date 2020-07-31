<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Task Handlers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-handler-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Task Handler', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cc_task_handler_id',
            'cc_task_handler_type',
            'cc_task_handler_class',
            'cc_task_handler_from_system',
            'cc_task_handler_status',
            //'cc_task_handler_count',
            //'cc_task_handler_create_at',
            //'cc_task_handler_update_at',
            //'cc_task_handler_desc',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

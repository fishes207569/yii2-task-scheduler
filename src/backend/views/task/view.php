<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model ccheng\task\common\models\Task */
/* @var $taskTypeMap array */

$this->title = '任务详情';
$this->params['breadcrumbs'][] = ['label' => '异步任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

    <p>
        <?= Html::a('返回', \Yii::$app->request->referrer, ['class' => 'btn btn-default pull-right','style'=>'margin-left:10px']) ?>
        <?= Html::a('更新', ['update', 'id' => $model->cc_task_id], ['class' => 'btn btn-primary pull-right']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cc_task_id',
            [
                'attribute' => 'cc_task_type',
                'format' => 'raw',
                'value' => function ($model) use ($taskTypeMap) {
                    return isset($taskTypeMap[$model->cc_task_type]) ? $taskTypeMap[$model->cc_task_type] : '-';
                }
            ],
            'cc_task_key',
            [
                'attribute' => 'cc_task_from_system',
                'format' => 'raw',
                'value' => function ($model) {
                    return \ccheng\task\common\enums\SystemEnum::getValue($model->cc_task_from_system);
                }
            ],
            [
                'attribute' => 'cc_task_request_data',
                'format' => 'raw',
                'value' => function ($model) {
                    return \kdn\yii2\JsonEditor::widget([
                        'model' => $model,
                        'name' => 'cc_task_request_data',
                        'attribute' => 'cc_task_request_data',
                        'value' => $model->cc_task_request_data,
                        'clientOptions' => ['modes' => ['tree'], 'mode' => 'tree'],

                    ]);
                }
            ],
            [
                'attribute' => 'cc_task_response_data',
                'format' => 'raw',
                'value' => function ($model) {
                    return \kdn\yii2\JsonEditor::widget(
                        [
                            'model' => $model,
                            'name' => 'cc_task_response_data',
                            'attribute' => 'cc_task_response_data',
                            'value' => $model->cc_task_response_data,
                            'clientOptions' => ['modes' => ['tree'], 'mode' => 'tree'],

                        ]);
                }
            ],
            'cc_task_execute_log:ntext',
            [
                'attribute' => 'cc_task_status',
                'format' => 'raw',
                'value' => function ($model) {
                    return \ccheng\task\common\enums\TaskStatusEnum::getValue($model->cc_task_status);
                }
            ],
            'cc_task_next_run_time:datetime',
            'cc_task_retry_times:datetime',
            'cc_task_create_at',
            'cc_task_update_at',
            'cc_task_suspend_times:datetime',
            'cc_task_priority',
            'cc_task_queue_id',
            'cc_task_abort_time:datetime',
            'cc_task_create_at:datetime',
            'cc_task_update_at:datetime',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use ccheng\task\common\enums\TaskStatusEnum;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">

        <?php echo $this->render('_search', [
            'model' => $searchModel,
            'taskTypeMap' => $taskTypeMap,
            'taskStatusMap' => $taskStatusMap,
            'formSystemMap' => $formSystemMap,
        ]) ?>

    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title ?>列表</h3>
                <div class="box-tools pull-right">
                    <?= Html::a('新建任务', ['create'], ['class' => "btn btn-success btn-sm", 'style' => "margin-right:50px;margin-top:12px"]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,

                    'columns' => [
                        'cc_task_id',
                        [
                            'attribute' => 'cc_task_from_system',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return \ccheng\task\common\enums\SystemEnum::getValue($model->cc_task_from_system);
                            }
                        ],
                        'taskHandler.cc_task_handler_desc',
                        'cc_task_key',

                        'cc_task_next_run_time:datetime',
                        'cc_task_abort_time:datetime',
                        'cc_task_create_at:datetime',
                        'cc_task_update_at:datetime',
                        //'cc_task_suspend_times:datetime',
                        'cc_task_priority',
                        //'cc_task_queue_id',
                        [
                            'attribute' => 'cc_task_status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return TaskStatusEnum::getValue($model->cc_task_status);
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-right'],
                            'template' => '{exec} {add} {update} {view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a('详情', $url, ['class' => 'btn btn-default btn-sm']);
                                },
                                'update' => function ($url, $model, $key) {
                                    return Html::a('编辑', $url, ['class' => 'btn btn-primary btn-sm']);
                                },
                                'add' => function ($url, $model, $key) {
                                    return Html::a('加入队列', $url, ['class' => 'btn btn-warning btn-sm']);
                                },
                                'exec' => function ($url, $model, $key) {
                                    return Html::a('立即执行', $url, ['class' => 'btn btn-danger btn-sm']);
                                }
                            ],
                            'visibleButtons' => [
                                'add' => function ($model) {
                                    return in_array($model->cc_task_status, [TaskStatusEnum::TASK_STATUS_OPEN, TaskStatusEnum::TASK_STATUS_ERROR]) && !$model->cc_task_queue_id;
                                },
                                'exec' => function ($model) {
                                    return in_array($model->cc_task_status, [TaskStatusEnum::TASK_STATUS_OPEN, TaskStatusEnum::TASK_STATUS_ERROR]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
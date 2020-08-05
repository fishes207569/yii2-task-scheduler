<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title ?>列表</h3>
                <div class="box-tools pull-right">
                    <?= Html::a('添加', ['create'], ['class' => "btn btn-success btn-sm", 'style' => "margin-right:50px;margin-top:12px"]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'cc_task_id',
                        'taskHandler.cc_task_handler_desc',
                        'cc_task_key',
                        'cc_task_from_system',
//                        'cc_task_request_data',
                        //'cc_task_response_data',
                        //'cc_task_execute_log:ntext',
                        [
                            'attribute' => 'cc_task_status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return \ccheng\task\common\enums\TaskStatusEnum::getValue($model->cc_task_status);
                            }
                        ],
                        'cc_task_next_run_time:datetime',
                        'cc_task_abort_time:datetime',
                        'cc_task_create_at:datetime',
                        'cc_task_update_at:datetime',
                        //'cc_task_suspend_times:datetime',
                        'cc_task_priority',
                        //'cc_task_queue_id',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{view} {update}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a('详情', $url, ['class' => 'btn btn-default btn-sm']);
                                },
                                'update' => function ($url, $model, $key) {
                                    return Html::a('编辑', $url, ['class' => 'btn btn-primary btn-sm']);
                                }
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
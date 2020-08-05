<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务处理器';
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
                        'cc_task_handler_id',
                        'cc_task_handler_type',
                        'cc_task_handler_desc',
                        'cc_task_handler_class',
                        'cc_task_handler_from_system',
                        [
                            'attribute' => 'cc_task_handler_status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return \ccheng\task\common\enums\StatusEnum::getValue($model->cc_task_handler_status);
                            }
                        ],
                        'cc_task_handler_count',
                        'cc_task_handler_create_at:datetime',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{update}',
                            'buttons' => [
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
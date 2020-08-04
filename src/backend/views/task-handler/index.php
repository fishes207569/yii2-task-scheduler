<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Task Handlers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title ?></h3>
                <div class="box-tools pull-right">
                    <?= Html::a('<i class="icon ion-plus"></i>' . '添加', ['create'], ['class' => "btn btn-primary btn-xs"]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">

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
        </div>
    </div>
</div>


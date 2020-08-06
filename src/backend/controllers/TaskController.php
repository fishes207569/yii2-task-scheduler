<?php

namespace ccheng\task\backend\controllers;

use ccheng\task\backend\models\forms\TaskSearch;
use ccheng\task\common\enums\SystemEnum;
use ccheng\task\common\enums\TaskStatusEnum;
use ccheng\task\common\services\TaskService;
use Yii;
use ccheng\task\common\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    public $layout = 'mini';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $queryParams = \Yii::$app->request->queryParams;
        if (empty($queryParams['TaskSearch']['start_time']) && empty($queryParams['TaskSearch']['end_time'])) {
            if (empty($queryParams['TaskSearch']['start_time'])) {
                $queryParams['TaskSearch']['start_time'] = date('Y-m-d') . ' 00:00:00';
            }
            if (empty($queryParams['TaskSearch']['end_time'])) {
                $queryParams['TaskSearch']['end_time'] = date('Y-m-d') . ' 23:59:59';
            }
        }

        $dataProvider = $searchModel->search($queryParams);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'formSystemMap' => SystemEnum::getMap(),
            'taskTypeMap' => TaskService::getTaskTypeMap(),
            'taskStatusMap' => TaskStatusEnum::getMap(),
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'taskTypeMap' => TaskService::getTaskTypeMap()
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cc_task_id]);
        }
        !$model->cc_task_from_system && $model->cc_task_from_system = SystemEnum::SYSTEM_VIAUDIO;
        !$model->cc_task_status && $model->cc_task_status = TaskStatusEnum::TASK_STATUS_OPEN;
        !$model->cc_task_next_run_time && $model->cc_task_next_run_time = date('Y-m-d H:i:s', strtotime('+5 min'));
        return $this->render('create', [
            'model' => $model,
            'taskTypeMap' => TaskService::getTaskTypeMap()
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $a = \Yii::$app->request->referrer;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cc_task_id]);
        }
        !$model->cc_task_status && $model->cc_task_status = TaskStatusEnum::TASK_STATUS_OPEN;
        return $this->render('update', [
            'model' => $model,
            'taskTypeMap' => TaskService::getTaskTypeMap()
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();


        return $this->redirect(['index']);
    }

    public function actionExec($id)
    {
        try {
            $res = false;
            if ($task = $this->findModel($id)) {
                if ($task->cc_task_status == TaskStatusEnum::TASK_STATUS_OPEN) {
                    if($task->cc_task_queue_id){
                        if (\Yii::$app->queue->isWaiting($task->cc_task_queue_id)) {
                            \Yii::$app->queue->remove($task->cc_task_queue_id);
                            return TaskService::process($task);
                        }
                    }else{
                        TaskService::process($task);
                    }
                    $res = true;
                }
            }
            if ($res) {
                \Yii::$app->session->setFlash('success', '执行成功');
            } else {
                \Yii::$app->session->setFlash('error', '执行失败');
            }
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('error', '执行失败:' . $e->getMessage());
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionAdd($id)
    {
        $task = $this->findModel($id);
        if (!$task->cc_task_queue_id) {
            $message_id = TaskService::addToQueue($task);
            $delay = $task->cc_task_next_run_time >= time() ? $task->cc_task_next_run_time - time() : 1;
            $task->cc_task_queue_id = $message_id;
            if ($task->save()) {
                \Yii::$app->session->setFlash('success', '加入队列成功，将在' . $delay . ' 秒后执行');
            }
        } else {
            \Yii::$app->session->setFlash('error', '任务已加入队列，请刷新后查看');
        }
        return $this->redirect(\Yii::$app->request->referrer);

    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

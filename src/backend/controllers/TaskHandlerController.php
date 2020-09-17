<?php

namespace ccheng\task\backend\controllers;

use ccheng\task\common\enums\StatusEnum;
use ccheng\task\common\enums\SystemEnum;
use Yii;
use ccheng\task\common\models\TaskHandler;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskHandlerController implements the CRUD actions for TaskHandler model.
 */
class TaskHandlerController extends Controller
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
     * Lists all TaskHandler models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TaskHandler::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TaskHandler model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TaskHandler model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TaskHandler();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cc_task_handler_id]);
        }
        !$model->cc_task_handler_from_system && $model->cc_task_handler_from_system = SystemEnum::SYSTEM_MEDIA;
        is_null($model->cc_task_handler_status) && $model->cc_task_handler_status = StatusEnum::STATUS_ENABLE;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TaskHandler model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->cc_task_handler_id]);
        }
        !$model->cc_task_handler_from_system && $model->cc_task_handler_from_system = SystemEnum::SYSTEM_MEDIA;
        is_null($model->cc_task_handler_status) && $model->cc_task_handler_status = StatusEnum::STATUS_ENABLE;

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TaskHandler model.
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

    /**
     * Finds the TaskHandler model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TaskHandler the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TaskHandler::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

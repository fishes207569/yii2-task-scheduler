<?php

namespace ccheng\task\api\controllers;

use ccheng\task\api\models\searchs\task\indexSearch;
use ccheng\task\common\helpers\ModelHelpers;
use ccheng\task\common\models\Task;
use ccheng\task\common\services\TaskService;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UnprocessableEntityHttpException;

class TaskController extends BaseController
{
    public $modelClass = Task::class;

    public function actionIndex()
    {
        $form = new indexSearch();
        $form->load($this->params, '');
        return $form->getList();
    }

    public function actionCreate()
    {

        try {
            if ($task = TaskService::saveTask($this->params)) {
                return $task;
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

    }

    public function actionUpdate()
    {
        /** @var ActiveRecord $task */
        $task = $this->getModel();
        try {
            if ($task = TaskService::updateTask($task, $this->params)) {
                return $task;
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function actionDelete()
    {
        try {
            /** @var ActiveRecord $task */
            $task = $this->getModel();
            TaskService::deleteTask($task);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

    }

    public function actionView()
    {
        return $this->getModel();

    }

    public function actionExecture()
    {
        try {
            $task = $this->getModel();
            return TaskService::executeTask($task);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

    }
}
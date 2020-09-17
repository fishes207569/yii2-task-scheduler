<?php

namespace ccheng\task\api\controllers;

use ccheng\task\api\models\searchs\task_handler\indexSearch;
use ccheng\task\common\helpers\ModelHelpers;
use ccheng\task\common\models\TaskHandler;
use ccheng\task\common\services\TaskHandlerService;
use yii\web\Controller;
use yii\web\UnprocessableEntityHttpException;

class TaskHandlerController extends BaseController
{
    
    public $modelClass = TaskHandler::class;

    public function actionIndex()
    {
        $form = new indexSearch();
        $form->load($this->params, '');
        return $form->getList();
    }

    public function actionCreate()
    {
        $model = new $this->modelClass();
        if ($model->load($this->params, '') && $model->save()) {
            return $model;
        } else {
            $error = ModelHelpers::getModelError($model);
            throw new UnprocessableEntityHttpException($error);
        }
    }

    public function actionUpdate()
    {
        /** @var TaskHandler $model */
        $model = $this->getModel();
        $model->load($this->params, '');
        if (!empty($model->dirtyAttributes)) {
            if ($model->validate() && $model->save(false)) {
                return $model;
            } else {
                $error = ModelHelpers::getModelError($model);
                throw new UnprocessableEntityHttpException($error);
            }
        } else {
            throw new UnprocessableEntityHttpException('数据未更新');
        }
    }

    public function actionDelete()
    {
        /** @var TaskHandler $model */
        $model = $this->getModel();
        if ($model->cc_task_handler_count > 0) {
            throw new UnprocessableEntityHttpException('已存在相关任务，不能删除');
        } else {
            return $model->delete();
        }
    }

    public function actionMap()
    {
        $map = TaskHandlerService::getMap();
        return ['maps' => $map ?: []];
    }
}
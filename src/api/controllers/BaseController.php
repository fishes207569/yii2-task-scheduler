<?php

namespace ccheng\task\api\controllers;

use ccheng\task\common\models\Task;
use common\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class BaseController extends Controller
{

    public $enableCsrfValidation = false;
    public $params;
    
    /** @var ActiveRecord */
    public $modelClass;

    public function init()
    {
        $get_params = \Yii::$app->request->getQueryParams();
        $post_params = \Yii::$app->request->getBodyParams();
        $this->params = ArrayHelper::merge($get_params, $post_params);
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function getModel()
    {
        $primaryKey = current($this->modelClass::primaryKey());
        if(isset($this->params[$primaryKey])){
            return $this->modelClass::findOne($this->params[$primaryKey]);
        }else{
            throw new BadRequestHttpException('数据不存在');
        }

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[]=[
            'class' => TimestampBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
            ],
        ];
        $behaviors_callback = \Yii::$app->params['behaviors'];
        if (is_callable($behaviors_callback, false)) {
            $config_behaviors = call_user_func_array($behaviors_callback, [$this]);
            if(is_array($config_behaviors)){
                $behaviors = array_merge($behaviors, $config_behaviors);
            } 
        }
        return $behaviors;
    }
}

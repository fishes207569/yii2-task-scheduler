<?php

namespace ccheng\task\api\models\searchs\task_handler;

use ccheng\task\common\base\BaseListSearch;
use ccheng\task\common\enums\TaskStatusEnum;
use ccheng\task\common\enums\SystemEnum;
use ccheng\task\common\enums\StatusEnum;
use ccheng\task\common\models\Task;
use ccheng\task\common\models\TaskHandler;
use yii\db\ActiveRecord;
use yii\db\Expression;

class indexSearch extends BaseListSearch
{

    public $query;

    public $cc_task_handler_type;

    public $cc_task_handler_from_system = SystemEnum::SYSTEM_MEDIA;


    public $cc_task_handler_status;

    /** @var ActiveRecord */
    public $modelClass = TaskHandler::class;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['cc_task_handler_from_system', 'in', 'range' => SystemEnum::getKeys()],
            ['cc_task_handler_status', 'in', 'range' => StatusEnum::getKeys()]
        ]);
    }


    public function search()
    {
        $this->query = $this->modelClass::find()
            ->filterWhere([
                'cc_task_handler_from_system' => $this->cc_task_handler_from_system,
                'cc_task_handler_status' => $this->cc_task_handler_status,
                'cc_task_handler_type' => $this->cc_task_handler_type])
            ->orderBy($this->order_by);

        $this->calcPageData($count = $this->query->count());
        if($count){
            $column = $this->modelClass::getTableSchema()->getColumnNames();
            $column['cc_task_handler_create_at']=new Expression("FROM_UNIXTIME(cc_task_handler_create_at,'%Y-%m-%d %H:%i:%S')");
            $column['cc_task_handler_update_at']=new Expression("FROM_UNIXTIME(cc_task_handler_update_at,'%Y-%m-%d %H:%i:%S')");
            $this->query->select($column);
            return $this->query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }else{
            return [];
        }
    }
}
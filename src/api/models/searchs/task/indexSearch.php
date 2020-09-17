<?php

namespace ccheng\task\api\models\searchs\task;

use ccheng\task\common\base\BaseListSearch;
use ccheng\task\common\enums\SystemEnum;
use ccheng\task\common\enums\TaskStatusEnum;
use ccheng\task\common\models\Task;
use yii\db\Expression;

class indexSearch extends BaseListSearch
{

    public $query;

    public $cc_task_from_system = SystemEnum::SYSTEM_MEDIA;

    public $cc_task_type;

    public $cc_task_key;

    public $start_time;

    public $end_time;

    public $cc_task_status;

    public $modelClass = Task::class;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['cc_task_from_system', 'in', 'range' => SystemEnum::getKeys()],
            ['cc_task_status', 'in', 'range' => TaskStatusEnum::getKeys()],
            [['cc_task_type', 'cc_task_key'], 'string'],
            [['start_time', 'end_time'], 'filter', 'filter' => function ($value) {
                return $value ? strtotime($value) : $value;
            }],
        ]);
    }


    public function search()
    {
        $this->query = $this->modelClass::find()
            ->filterWhere([
                'cc_task_from_system' => $this->cc_task_from_system,
                'cc_task_status' => $this->cc_task_status,
                'cc_task_type' => $this->cc_task_type,
                'cc_task_key' => $this->cc_task_key
            ])
            ->andFilterWhere(['>=', 'cc_task_next_run_time', $this->start_time])
            ->andFilterWhere(['<=', 'cc_task_next_run_time', $this->end_time])
            ->orderBy($this->order_by);
        $this->calcPageData($count = $this->query->count());
        if($count){
            $column = $this->modelClass::getTableSchema()->getColumnNames();
            $column['cc_task_next_run_time']=new Expression("FROM_UNIXTIME(cc_task_next_run_time,'%Y-%m-%d %H:%i:%S')");
            $column['cc_task_abort_time']=new Expression("FROM_UNIXTIME(cc_task_abort_time,'%Y-%m-%d %H:%i:%S')");
            $column['cc_task_create_at']=new Expression("FROM_UNIXTIME(cc_task_create_at,'%Y-%m-%d %H:%i:%S')");
            $column['cc_task_update_at']=new Expression("FROM_UNIXTIME(cc_task_update_at,'%Y-%m-%d %H:%i:%S')");
            $this->query->select($column);
            return $this->query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }else{
            return [];
        }

    }
}
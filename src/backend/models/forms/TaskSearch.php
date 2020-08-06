<?php

namespace ccheng\task\backend\models\forms;

use ccheng\task\common\enums\SystemEnum;
use ccheng\task\common\enums\TaskStatusEnum;
use ccheng\task\common\models\Task;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TaskSearch extends Model
{

    public $form_system;

    public $task_type;

    public $start_time;

    public $end_time;

    public $task_status;

    public $task_key;

    public function rules()
    {
        return [
            ['form_system', 'in', 'range' => SystemEnum::getKeys()],
            ['task_status', 'in', 'range' => TaskStatusEnum::getKeys()],
            [['task_type', 'task_key'], 'string'],
            [['start_time', 'end_time'], 'filter', 'filter' => function ($value) {
                return $value ? strtotime($value) : $value;
            }],
        ];
    }

    public function search($params)
    {
        $query = Task::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->filterWhere(['cc_task_status' => $this->task_status]);
        $query->andFilterWhere(['cc_task_type' => $this->task_type]);
        $query->andFilterWhere(['cc_task_key' => $this->task_key]);
        $query->andFilterWhere(['cc_task_from_system' => $this->form_system]);
        $query->andFilterWhere(['>=', 'cc_task_next_run_time', $this->start_time]);
        $query->andFilterWhere(['<=', 'cc_task_next_run_time', $this->end_time]);
        $sql = $query->createCommand()->getRawSql();
        return $dataProvider;
    }


}
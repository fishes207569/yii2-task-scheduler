<?php

namespace ccheng\task\common\components;

use ccheng\task\common\models\Task;
use ccheng\task\common\services\TaskService;
use yii\base\Component;
use yii\web\UnprocessableEntityHttpException;

class AsyncTask extends Component
{
    /**
     * @param String $key
     * @return TaskService|null
     * @throws UnprocessableEntityHttpException
     */
    public function findByKey(String $key)
    {
        try {
            return TaskService::findTaskByKey($key);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return bool|Task|null   
     * @throws UnprocessableEntityHttpException
     */
    public function create(Array $data)
    {
        try {
            if ($task = TaskService::saveTask($this->params)) {
                return $task;
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param Task $task
     * @param array $data
     * @return bool|Task|false|int
     * @throws UnprocessableEntityHttpException
     * @throws \Throwable
     */
    public function update(Task $task, Array $data)
    {
        try {
            if ($task = TaskService::updateTask($task, $data)) {
                return $task;
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param Task $task
     * @return bool|false|int
     * @throws UnprocessableEntityHttpException
     * @throws \Throwable
     */
    public function delete(Task $task)
    {
        try {
            return TaskService::deleteTask($task);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param Task $task
     * @return bool|void
     * @throws UnprocessableEntityHttpException
     */
    public function execture(Task $task)
    {
        try {
            return TaskService::executeTask($task);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
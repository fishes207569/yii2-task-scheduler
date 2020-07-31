Task Scheduler for Yii2
=================
Task Scheduler By Ccheng

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ccheng/yii2-task-scheduler "*"
```

or add

```
"ccheng/yii2-task-scheduler": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

执行数据迁移以添加表结构:

```shell
yii migrate --migrationPath=@vendor/ccheng/yii2-task-scheduler/src/console/migrations
```

依赖
任务的处理依赖 yii2-queue 自行配置队列与job

定时任务

main.php 文件 controllerMap 中加入控制器映射
```php
	'task' => [
		'class' => \ccheng\task\console\controllers\TaskQueueController::class,
	],
```

加入定时任务 
将执行时间一分钟以内的任务放入队列
```shell
php yii task/scheduler
```

添加事件管理模块
```php
return [
	'modules' => [
		'task' => [
                'class'=>'ccheng\task\backend\Module',
		]
		...
	]
];
```

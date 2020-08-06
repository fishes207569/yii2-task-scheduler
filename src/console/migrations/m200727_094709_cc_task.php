<?php

use yii\db\Migration;

class m200727_094709_cc_task extends Migration
{
    public function up()
    {
        $sql = <<<EOF
        CREATE TABLE `cc_task_handler` (
          `cc_task_handler_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `cc_task_handler_type` varchar(64) NOT NULL COMMENT '类型',
          `cc_task_handler_class` varchar(255) NOT NULL COMMENT '实现类',
          `cc_task_handler_from_system` varchar(16) DEFAULT NULL COMMENT '来源系统',
          `cc_task_handler_status` varchar(16) DEFAULT NULL COMMENT '状态',
          `cc_task_handler_count` int(11) unsigned DEFAULT '0' COMMENT '任务计数',
          `cc_task_handler_create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
          `cc_task_handler_update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
          `cc_task_handler_desc` varchar(255) NOT NULL COMMENT '任务描述',
          PRIMARY KEY (`cc_task_handler_id`),
          UNIQUE KEY `idx_handler_type` (`cc_task_handler_type`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='任务处理器配置表';
        CREATE TABLE `cc_task` (
          `cc_task_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `cc_task_type` varchar(50) DEFAULT NULL COMMENT '任务类型',
          `cc_task_key` varchar(50) DEFAULT NULL COMMENT '任务键值',
          `cc_task_from_system` varchar(50) DEFAULT NULL COMMENT '来源系统',
          `cc_task_request_data` json DEFAULT NULL COMMENT '任务参数',
          `cc_task_response_data` json DEFAULT NULL COMMENT '任务响应数据',
          `cc_task_execute_log` text COMMENT '任务执行日志',
          `cc_task_status` varchar(16) NOT NULL DEFAULT 'open' COMMENT '任务状态',
          `cc_task_next_run_time` int(11) NOT NULL DEFAULT '0' COMMENT '下次重试时间',
          `cc_task_retry_times` int(11) NOT NULL DEFAULT '0' COMMENT '重试次数',
          `cc_task_create_at` int(11) unsigned DEFAULT NULL COMMENT '创建时间',
          `cc_task_update_at` int(11) unsigned DEFAULT NULL COMMENT '更新时间',
          `cc_task_suspend_times` int(11) unsigned DEFAULT '0' COMMENT '挂起次数',
          `cc_task_priority` int(11) unsigned DEFAULT '1' COMMENT '优先级',
          `cc_task_queue_id` int(11) unsigned DEFAULT '0' COMMENT '任务队列ID',
          `cc_task_abort_time` int(11) unsigned DEFAULT '0' COMMENT '最后截止时间',
          PRIMARY KEY (`cc_task_id`) USING BTREE,
          KEY `idx_task_key_type` (`cc_task_type`,`cc_task_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务计划表';
        CREATE TABLE `cc_task_crud_log` (
          `cc_task_crud_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `cc_task_crud_log_task_id` int(11) DEFAULT NULL COMMENT '任务ID',
          `cc_task_crud_log_type` varchar(16) DEFAULT NULL COMMENT '操作类型',
          `cc_task_crud_log_old_value` text COMMENT '旧值',
          `cc_task_crud_log_new_value` text COMMENT '新值',
          `cc_task_crud_log_operator` int(11) DEFAULT NULL COMMENT '操作人员',
          `cc_task_crud_log_create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
          `cc_task_crud_log_update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
          PRIMARY KEY (`cc_task_crud_log_id`),
          KEY `idx_task_type_id` (`cc_task_crud_log_task_id`,`cc_task_crud_log_type`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='任务变更日志表';
EOF;
        return $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<EOF
DROP TABLE `cc_task`;DROP TABLE `cc_task_handler`;DROP TABLE `cc_task_crud_log`;
EOF;
        $this->execute($sql);
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

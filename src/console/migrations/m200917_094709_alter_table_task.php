<?php

use yii\db\Migration;

class m200917_094709_alter_table_task extends Migration
{
    public function safeUp()
    {
        $sql = <<<EOF
       ALTER TABLE `cc_task` ADD COLUMN `cc_task_version` bigint(11) unsigned DEFAULT '0' COMMENT '版本号'
EOF;
       return $this->execute($sql);
    }

    public function safeDown()
    {
        $sql = <<<EOF
            ALTER TABLE `cc_task` DROP COLUMN `cc_task_version`
EOF;
        return $this->execute($sql);

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

<?php

use yii\db\Migration;

class m260310_040000_create_qualifications_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%qualifications}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->tinyInteger()->defaultValue(1)->comment('1=Active, 0=Inactive'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function safeDown()
    {
        $this->dropTable('{{%qualifications}}');
    }
}

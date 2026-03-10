<?php

use yii\db\Migration;

class m260310_040006_create_exam_rounds_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%exam_rounds}}', [
            'id' => $this->primaryKey(),
            'year' => $this->integer()->notNull()->comment('ปี พ.ศ.'),
            'round_number' => $this->integer()->notNull()->comment('รอบที่'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function safeDown()
    {
        $this->dropTable('{{%exam_rounds}}');
    }
}

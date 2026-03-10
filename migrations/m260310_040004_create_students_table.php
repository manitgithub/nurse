<?php

use yii\db\Migration;

class m260310_040004_create_students_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%students}}', [
            'student_id' => $this->string(20)->notNull(),
            'fullname' => $this->string(255)->notNull(),
            'high_school' => $this->string(255),
            'gpax_hs' => $this->decimal(3, 2)->comment('GPAX มัธยม'),
            'hometown' => $this->string(255),
            'status' => $this->string(50)->defaultValue('active')->comment('active, inactive, graduated, dropped'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addPrimaryKey('pk_students', '{{%students}}', 'student_id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%students}}');
    }
}

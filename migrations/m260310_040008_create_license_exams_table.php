<?php

use yii\db\Migration;

class m260310_040008_create_license_exams_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%license_exams}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->string(20)->notNull(),
            'status' => $this->string(50)->defaultValue('pending')->comment('pending, passed, failed'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey(
            'fk_license_exams_student',
            '{{%license_exams}}',
            'student_id',
            '{{%students}}',
            'student_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%license_exams}}');
    }
}

<?php

use yii\db\Migration;

class m260310_040005_create_student_grades_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%student_grades}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->string(20)->notNull(),
            'academic_year' => $this->string(10)->notNull()->comment('ปีการศึกษา เช่น 2568'),
            'gpax' => $this->decimal(3, 2),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey(
            'fk_student_grades_student',
            '{{%student_grades}}',
            'student_id',
            '{{%students}}',
            'student_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%student_grades}}');
    }
}

<?php

use yii\db\Migration;

class m260310_040007_create_exam_results_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%exam_results}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->string(20)->notNull(),
            'round_id' => $this->integer()->notNull(),
            'subject_1_score' => $this->decimal(5, 2),
            'subject_2_score' => $this->decimal(5, 2),
            'subject_3_score' => $this->decimal(5, 2),
            'subject_4_score' => $this->decimal(5, 2),
            'subject_5_score' => $this->decimal(5, 2),
            'subject_6_score' => $this->decimal(5, 2),
            'subject_7_score' => $this->decimal(5, 2),
            'subject_8_score' => $this->decimal(5, 2),
            'subject_9_score' => $this->decimal(5, 2),
            'subject_10_score' => $this->decimal(5, 2),
            'status' => $this->string(50)->defaultValue('pending')->comment('pending, passed, failed'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey(
            'fk_exam_results_student',
            '{{%exam_results}}',
            'student_id',
            '{{%students}}',
            'student_id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_exam_results_round',
            '{{%exam_results}}',
            'round_id',
            '{{%exam_rounds}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%exam_results}}');
    }
}

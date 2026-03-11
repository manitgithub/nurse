<?php

use yii\db\Migration;

class m260311_010000_seed_exam_data extends Migration
{
    public function safeUp()
    {
        // 1. Exam Rounds
        $this->batchInsert('{{%exam_rounds}}', ['year', 'round_number'], [
            [2567, 1],
            [2567, 2],
            [2566, 1],
            [2566, 2],
        ]);

        // 2. Exam Results for sample students
        // We know student IDs from previous seeding: 66101001, 66101002, 65101003, 64101004
        // We assume round IDs are 1, 2, 3, 4 based on batchInsert above

        $this->batchInsert(
            '{{%exam_results}}',
            ['student_id', 'round_id', 'subject_1_score', 'subject_2_score', 'subject_3_score', 'subject_4_score', 'subject_5_score', 'status'],
            [
                ['66101001', 1, 75.50, 80.00, 68.50, 82.00, 70.00, 'passed'],
                ['66101002', 1, 88.00, 85.50, 90.00, 87.00, 84.50, 'passed'],
                ['65101003', 3, 60.00, 55.50, 62.00, 58.00, 65.00, 'pending'],
                ['64101004', 4, 82.00, 78.50, 80.00, 85.00, 88.00, 'passed'],
            ]
        );
    }

    public function safeDown()
    {
        $this->delete('{{%exam_results}}', ['student_id' => ['66101001', '66101002', '65101003', '64101004']]);
        $this->delete('{{%exam_rounds}}', ['year' => [2567, 2566]]);
    }
}

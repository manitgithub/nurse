<?php

use yii\db\Migration;

class m260311_020000_seed_gpax_data extends Migration
{
    public function safeUp()
    {
        $students = (new \yii\db\Query())
            ->select(['student_id', 'batch'])
            ->from('{{%students}}')
            ->all();

        $academicYears = ['2565', '2566', '2567'];

        foreach ($students as $student) {
            foreach ($academicYears as $year) {
                // Only seed if batch year matches or is later
                if (intval($year) >= intval($student['batch'])) {
                    $this->insert('{{%student_grades}}', [
                        'student_id' => $student['student_id'],
                        'academic_year' => $year,
                        'gpax' => mt_rand(250, 395) / 100, // Random GPAX 2.50 - 3.95
                    ]);
                }
            }
        }
    }

    public function safeDown()
    {
        $this->delete('{{%student_grades}}');
    }
}

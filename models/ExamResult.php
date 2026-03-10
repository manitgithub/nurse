<?php

namespace app\models;

use yii\db\ActiveRecord;

class ExamResult extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%exam_results}}';
    }

    public function rules()
    {
        return [
            [['student_id', 'round_id'], 'required'],
            [['student_id'], 'string', 'max' => 20],
            [['round_id'], 'integer'],
            [
                [
                    'subject_1_score',
                    'subject_2_score',
                    'subject_3_score',
                    'subject_4_score',
                    'subject_5_score',
                    'subject_6_score',
                    'subject_7_score',
                    'subject_8_score',
                    'subject_9_score',
                    'subject_10_score'
                ],
                'number'
            ],
            [['status'], 'string', 'max' => 50],
            [['student_id'], 'exist', 'targetClass' => Student::class, 'targetAttribute' => 'student_id'],
            [['round_id'], 'exist', 'targetClass' => ExamRound::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'รหัสนักศึกษา',
            'round_id' => 'รอบสอบ',
            'subject_1_score' => 'วิชาที่ 1',
            'subject_2_score' => 'วิชาที่ 2',
            'subject_3_score' => 'วิชาที่ 3',
            'subject_4_score' => 'วิชาที่ 4',
            'subject_5_score' => 'วิชาที่ 5',
            'subject_6_score' => 'วิชาที่ 6',
            'subject_7_score' => 'วิชาที่ 7',
            'subject_8_score' => 'วิชาที่ 8',
            'subject_9_score' => 'วิชาที่ 9',
            'subject_10_score' => 'วิชาที่ 10',
            'status' => 'สถานะ',
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['student_id' => 'student_id']);
    }

    public function getRound()
    {
        return $this->hasOne(ExamRound::class, ['id' => 'round_id']);
    }

    public function getTotalScore()
    {
        $total = 0;
        for ($i = 1; $i <= 10; $i++) {
            $attr = "subject_{$i}_score";
            $total += (float) $this->$attr;
        }
        return $total;
    }

    public static function getStatusList()
    {
        return [
            'pending' => 'รอผล',
            'passed' => 'สอบผ่าน',
            'failed' => 'สอบไม่ผ่าน',
        ];
    }
}

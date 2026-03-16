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
                ],
                'in',
                'range' => ['P', 'F']
            ],
            [['status'], 'string', 'max' => 50],
            [['student_id'], 'exist', 'targetClass' => Student::class, 'targetAttribute' => 'student_id'],
            [['round_id'], 'exist', 'targetClass' => ExamRound::class, 'targetAttribute' => 'id'],
        ];
    }

    public static function getSubjectLabels()
    {
        return [
            'subject_1' => 'การพยาบาลพื้นฐาน',
            'subject_2' => 'การพยาบาลผู้ใหญ่ 1',
            'subject_3' => 'การพยาบาลผู้ใหญ่ 2',
            'subject_4' => 'การพยาบาลเด็กและวัยรหัส',
            'subject_5' => 'การพยาบาลมารดา ทารก ฯ 1',
            'subject_6' => 'การพยาบาลมารดา ทารก ฯ 2',
            'subject_7' => 'การพยาบาลจิตเวชและสุขภาพจิต',
            'subject_8' => 'การพยาบาลชุมชน',
        ];
    }

    public function attributeLabels()
    {
        $subjects = self::getSubjectLabels();
        return [
            'id' => 'ID',
            'student_id' => 'รหัสนักศึกษา',
            'round_id' => 'รอบสอบ',
            'subject_1_score' => $subjects['subject_1'],
            'subject_2_score' => $subjects['subject_2'],
            'subject_3_score' => $subjects['subject_3'],
            'subject_4_score' => $subjects['subject_4'],
            'subject_5_score' => $subjects['subject_5'],
            'subject_6_score' => $subjects['subject_6'],
            'subject_7_score' => $subjects['subject_7'],
            'subject_8_score' => $subjects['subject_8'],
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

    public function getPassedCount()
    {
        $count = 0;
        for ($i = 1; $i <= 8; $i++) {
            $attr = "subject_{$i}_score";
            if ($this->$attr === 'P') {
                $count++;
            }
        }
        return $count;
    }

    public static function getPassFailList()
    {
        return [
            'P' => 'ผ่าน',
            'F' => 'ไม่ผ่าน',
        ];
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

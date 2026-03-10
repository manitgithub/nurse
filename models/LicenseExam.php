<?php

namespace app\models;

use yii\db\ActiveRecord;

class LicenseExam extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%license_exams}}';
    }

    public function rules()
    {
        return [
            [['student_id'], 'required'],
            [['student_id'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 50],
            [['student_id'], 'exist', 'targetClass' => Student::class, 'targetAttribute' => 'student_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'รหัสนักศึกษา',
            'status' => 'สถานะ',
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['student_id' => 'student_id']);
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

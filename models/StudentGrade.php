<?php

namespace app\models;

use yii\db\ActiveRecord;

class StudentGrade extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student_grades}}';
    }

    public function rules()
    {
        return [
            [['student_id', 'academic_year'], 'required'],
            [['student_id'], 'string', 'max' => 20],
            [['academic_year'], 'string', 'max' => 10],
            [['gpax'], 'number', 'min' => 0, 'max' => 4],
            [['student_id'], 'exist', 'targetClass' => Student::class, 'targetAttribute' => 'student_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'รหัสนักศึกษา',
            'academic_year' => 'ภาคเรียน / ปีการศึกษา',
            'gpax' => 'GPAX',
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['student_id' => 'student_id']);
    }
}

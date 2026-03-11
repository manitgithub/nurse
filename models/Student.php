<?php

namespace app\models;

use yii\db\ActiveRecord;

class Student extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%students}}';
    }

    public function rules()
    {
        return [
            [['student_id', 'fullname'], 'required'],
            [['student_id'], 'string', 'max' => 20],
            [['batch'], 'string', 'max' => 10],
            [['fullname', 'high_school', 'hometown'], 'string', 'max' => 255],
            [['gpax_hs'], 'number', 'min' => 0, 'max' => 4],
            [['status'], 'string', 'max' => 50],
            [['student_id'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'student_id' => 'รหัสนักศึกษา',
            'batch' => 'รุ่น',
            'fullname' => 'ชื่อ-นามสกุล',
            'high_school' => 'โรงเรียนมัธยม',
            'gpax_hs' => 'GPAX มัธยม',
            'hometown' => 'ภูมิลำเนา',
            'status' => 'สถานะ',
        ];
    }

    public function getStudentGrades()
    {
        return $this->hasMany(StudentGrade::class, ['student_id' => 'student_id']);
    }

    public function getExamResults()
    {
        return $this->hasMany(ExamResult::class, ['student_id' => 'student_id']);
    }

    public function getLicenseExams()
    {
        return $this->hasMany(LicenseExam::class, ['student_id' => 'student_id']);
    }

    public function getLatestGrade()
    {
        return $this->hasOne(StudentGrade::class, ['student_id' => 'student_id'])
            ->orderBy(['academic_year' => SORT_DESC]);
    }

    public static function getStatusList()
    {
        return [
            'active' => 'กำลังศึกษา',
            'inactive' => 'พักการเรียน',
            'graduated' => 'สำเร็จการศึกษา',
            'dropped' => 'พ้นสภาพ',
        ];
    }

    /**
     * Get distinct batch list for dropdown
     */
    public static function getBatchList()
    {
        $batches = self::find()
            ->select('batch')
            ->distinct()
            ->where(['not', ['batch' => null]])
            ->andWhere(['!=', 'batch', ''])
            ->orderBy(['batch' => SORT_DESC])
            ->column();
        return array_combine($batches, array_map(fn($b) => "รุ่น $b", $batches));
    }
}

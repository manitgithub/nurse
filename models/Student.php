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
            [['advisor_id'], 'integer'],
            [['student_id'], 'unique'],
            [['advisor_id'], 'exist', 'targetClass' => Personnel::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'student_id' => 'รหัสนักศึกษา',
            'batch' => 'รหัส',
            'fullname' => 'ชื่อ-นามสกุล',
            'high_school' => 'โรงเรียนมัธยม',
            'gpax_hs' => 'GPAX มัธยม',
            'hometown' => 'ภูมิลำเนา',
            'status' => 'สถานะ',
            'advisor_id' => 'อาจารย์ที่ปรึกษา',
        ];
    }

    public function getStudentGrades()
    {
        return $this->hasMany(StudentGrade::class, ['student_id' => 'student_id']);
    }

    public function getAdvisor()
    {
        return $this->hasOne(Personnel::class, ['id' => 'advisor_id']);
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
            ->orderBy(['id' => SORT_DESC]);
    }

    public function getAverageGpax()
    {
        return $this->getStudentGrades()->average('gpax');
    }

    /**
     * Get GPA history in chronological order (Year ASC, Semester ASC)
     */
    public function getGpaxHistoryChronological()
    {
        return $this->getStudentGrades()
            ->orderBy([
                new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', -1) ASC"),
                new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', 1) ASC"),
            ])
            ->all();
    }

    /**
     * Analyze GPA trend based on recent semesters
     * Returns ['status' => string, 'label' => string, 'color' => string, 'description' => string]
     */
    public function getGpaxTrend()
    {
        $grades = $this->getGpaxHistoryChronological();
        $count = count($grades);

        if ($count < 2) {
            return [
                'status' => 'stable',
                'label' => 'ข้อมูลไม่เพียงพอ',
                'color' => 'gray',
                'description' => 'ต้องการข้อมูลอย่างน้อย 2 เทอมเพื่อวิเคราะห์แนวโน้ม'
            ];
        }

        // Standard logic: Compare latest with previous
        $latest = $grades[$count - 1]->gpax;
        $previous = $grades[$count - 2]->gpax;
        $diff = $latest - $previous;

        if ($diff > 0.05) {
            return [
                'status' => 'improving',
                'label' => 'มีแนวโน้มดีขึ้น',
                'color' => 'emerald',
                'description' => 'ผลการเรียนเทอมล่าสุดเพิ่มขึ้นอย่างมีนัยสำคัญ (+ ' . number_format($diff, 2) . ')'
            ];
        } elseif ($diff < -0.05) {
            return [
                'status' => 'declining',
                'label' => 'มีแนวโน้มดรอปลง',
                'color' => 'rose',
                'description' => 'ผลการเรียนเทอมล่าสุดลดลงอย่างมีนัยสำคัญ (- ' . number_format(abs($diff), 2) . ')'
            ];
        } else {
            return [
                'status' => 'stable',
                'label' => 'คงที่',
                'color' => 'indigo',
                'description' => 'ผลการเรียนอยู่ในเกณฑ์คงที่เมื่อเทียบกับเทอมก่อนหน้า'
            ];
        }
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
        return array_combine($batches, array_map(fn($b) => "รหัส $b", $batches));
    }
}

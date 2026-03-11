<?php

namespace app\models;

use yii\db\ActiveRecord;

class AcademicService extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%academic_services}}';
    }

    public function rules()
    {
        return [
            [['activity_name'], 'required'],
            [['activity_name', 'project_focus'], 'string', 'max' => 500],
            [['fiscal_year'], 'string', 'max' => 10],
            [['project_type', 'budget_source', 'status'], 'string', 'max' => 100],
            [['budget_category', 'responsible_person'], 'string', 'max' => 255],
            [['strategic_goal', 'strategy_link', 'teacher_role', 'target_group', 'result_percentage', 'problems', 'solutions'], 'string'],
            [['budget_amount', 'latitude', 'longitude'], 'number'],
            [['participants_count'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['status'], 'default', 'value' => 'กำลังดำเนินการ'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_name' => 'กิจกรรม',
            'fiscal_year' => 'ประจำปีงบประมาณ',
            'strategic_goal' => 'เป้าหมายผลสัมฤทธิ์เชิงยุทธศาสตร์',
            'strategy_link' => 'ความเชื่อมโยง/ตอบสนองยุทธศาสตร์',
            'project_focus' => 'ประเด็นมุ่งเน้นของโครงการ',
            'project_type' => 'ลักษณะโครงการ',
            'budget_source' => 'แหล่งงบประมาณ',
            'budget_category' => 'หมวดงบประมาณที่ใช้',
            'budget_amount' => 'ค่าใช้จ่ายในโครงการ (บาท)',
            'start_date' => 'วันที่เริ่ม',
            'end_date' => 'วันที่สิ้นสุด',
            'teacher_role' => 'บทบาทของอาจารย์',
            'responsible_person' => 'ผู้รับผิดชอบโครงการ',
            'target_group' => 'กลุ่มผู้รับบริการ / พื้นที่จัดกิจกรรม',
            'result_percentage' => 'ผลการดำเนินงาน สรุปผลเป็น %',
            'participants_count' => 'จำนวนผู้เข้ารับบริการ (คน)',
            'problems' => 'ปัญหาอุปสรรค',
            'solutions' => 'แนวทางการแก้ไข',
            'status' => 'สถานะ',
            'latitude' => 'ละติจูด',
            'longitude' => 'ลองจิจูด',
        ];
    }

    public function getFiles()
    {
        return $this->hasMany(AcademicServiceFile::class, ['academic_service_id' => 'id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(AcademicServiceFile::class, ['academic_service_id' => 'id'])
            ->andWhere(['file_type' => 'attachment']);
    }

    public function getImages()
    {
        return $this->hasMany(AcademicServiceFile::class, ['academic_service_id' => 'id'])
            ->andWhere(['file_type' => 'image']);
    }
}

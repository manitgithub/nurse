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
            [['activity_name', 'project_focus', 'strategic_objective', 'qa_indicator_1', 'qa_indicator_2', 'qa_indicator_3', 'qa_indicator_4', 'qa_indicator_5', 'integration_student_activity_desc', 'integration_academic_service_desc', 'integration_research_desc', 'integration_other'], 'string', 'max' => 500],
            [['fiscal_year'], 'string', 'max' => 10],
            [['project_type', 'budget_source', 'status'], 'string', 'max' => 100],
            [['budget_category', 'responsible_person', 'strategic_number', 'integration_teaching_subject', 'integration_teaching_semester'], 'string', 'max' => 255],
            [['strategic_goal', 'strategy_link', 'teacher_role', 'target_group', 'result_percentage', 'problems', 'solutions', 'integration_problems', 'integration_solutions'], 'string'],
            [['budget_amount', 'latitude', 'longitude'], 'number'],
            [['participants_count'], 'integer'],
            [['integration_teaching', 'integration_student_activity', 'integration_academic_service', 'integration_research'], 'boolean'],
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
            'strategic_number' => 'ยุทธศาสตร์ที่ (ความเชื่อมโยง/ตอบสนองยุทธศาสตร์มวล.)',
            'strategic_objective' => 'เป้าประสงค์',
            'qa_indicator_1' => 'ตัวบ่งชี้ประกันคุณภาพ 1',
            'qa_indicator_2' => 'ตัวบ่งชี้ประกันคุณภาพ 2',
            'qa_indicator_3' => 'ตัวบ่งชี้ประกันคุณภาพ 3',
            'qa_indicator_4' => 'ตัวบ่งชี้ประกันคุณภาพ 4',
            'qa_indicator_5' => 'ตัวบ่งชี้ประกันคุณภาพ 5',
            'strategy_link' => 'ความเชื่อมโยง/ตอบสนองยุทธศาสตร์',
            'project_focus' => 'ประเด็นมุ่งเน้นของโครงการ',
            'project_type' => 'ลักษณะโครงการ',
            'budget_source' => 'งบประมาณจากแหล่ง',
            'budget_category' => 'แหล่งงบ',
            'budget_amount' => 'งบประมาณ (บาท)',
            'start_date' => 'วันที่เริ่ม',
            'end_date' => 'ถึงวันที่',
            'teacher_role' => 'บทบาทของอาจารย์',
            'responsible_person' => 'ผู้รับผิดชอบโครงการ',
            'target_group' => 'กลุ่มผู้รับบริการ / พื้นที่จัดกิจกรรม',
            'result_percentage' => 'ผลการดำเนินงาน สรุปผลเป็น %',
            'participants_count' => 'จำนวนผู้เข้าร่วม (คน)',
            'problems' => 'ปัญหาอุปสรรค',
            'solutions' => 'แนวทางการแก้ไข',
            'status' => 'สถานะ',
            'latitude' => 'ละติจูด',
            'longitude' => 'ลองจิจูด',
            'integration_teaching' => 'บูรณาการการเรียนการสอน',
            'integration_teaching_subject' => 'รายวิชา',
            'integration_teaching_semester' => 'ภาคการศึกษาที่',
            'integration_student_activity' => 'บูรณาการกับกิจกรรมนักศึกษา',
            'integration_student_activity_desc' => 'ระบุกิจกรรมนักศึกษา',
            'integration_academic_service' => 'บูรณาการกับงานบริการวิชาการ',
            'integration_academic_service_desc' => 'ระบุงานบริการวิชาการ',
            'integration_research' => 'บูรณาการงานวิจัย',
            'integration_research_desc' => 'ระบุงานวิจัย',
            'integration_other' => 'อื่นๆ ระบุ',
            'integration_problems' => 'ปัญหาอุปสรรค(หลังจากบูรณาการ)',
            'integration_solutions' => 'แนวทางการแก้ไข(หลังจากบูรณาการ)',
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

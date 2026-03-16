<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Personnel extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%personnels}}';
    }

    public function rules()
    {
        return [
            [['personnel_code', 'fullname'], 'required'],
            [['personnel_code'], 'string', 'max' => 50],
            [['personnel_code'], 'unique'],
            [['gender'], 'string', 'max' => 10],
            [['track'], 'string', 'max' => 20],
            [['resignation_year'], 'string', 'max' => 4],
            [['fullname'], 'string', 'max' => 255],
            [['academic_position', 'job_position'], 'string', 'max' => 100],
            [['start_date', 'contract_end_date', 'birth_date'], 'safe'],
            [['photo', 'license_file', 'member_card_file'], 'string', 'max' => 500],
            [['phone'], 'string', 'max' => 20],
            [['license_no', 'council_member_no'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['status'], 'integer'],
            [['qualification_id', 'contract_type_id', 'department_id', 'subject_group_id'], 'integer'],
            [['license_expire_date'], 'safe'],
            [['qualification_id'], 'exist', 'targetClass' => Qualification::class, 'targetAttribute' => 'id'],
            [['contract_type_id'], 'exist', 'targetClass' => ContractType::class, 'targetAttribute' => 'id'],
            [['department_id'], 'exist', 'targetClass' => Department::class, 'targetAttribute' => 'id'],
            [['subject_group_id'], 'exist', 'targetClass' => SubjectGroup::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'personnel_code' => 'รหัสบุคลากร',
            'gender' => 'เพศ',
            'track' => 'สาย',
            'fullname' => 'ชื่อ-นามสกุล',
            'academic_position' => 'ตำแหน่งทางวิชาการ',
            'job_position' => 'ตำแหน่งงาน',
            'start_date' => 'วันเริ่มงาน',
            'contract_end_date' => 'วันสิ้นสุดสัญญา',
            'birth_date' => 'วันเกิด',
            'photo' => 'รูปภาพ',
            'phone' => 'โทรศัพท์',
            'email' => 'อีเมล',
            'license_no' => 'เลขที่ใบอนุญาตประกอบวิชาชีพ',
            'council_member_no' => 'เลขที่สมาชิกสภาการพยาบาล',
            'license_expire_date' => 'วันหมดอายุใบอนุญาตฯ',
            'license_file' => 'ไฟล์ใบอนุญาตประกอบวิชาชีพ',
            'member_card_file' => 'ไฟล์บัตรสมาชิก',
            'status' => 'สถานะ',
            'qualification_id' => 'คุณวุฒิ',
            'contract_type_id' => 'ประเภทสัญญา',
            'department_id' => 'สาขา',
            'subject_group_id' => 'สาขาตามโครงสร้าง',
            'resignation_year' => 'ปีที่ลาออก',
        ];
    }

    public function getQualification()
    {
        return $this->hasOne(Qualification::class, ['id' => 'qualification_id']);
    }

    public function getContractType()
    {
        return $this->hasOne(ContractType::class, ['id' => 'contract_type_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'department_id']);
    }

    public function getSubjectGroup()
    {
        return $this->hasOne(SubjectGroup::class, ['id' => 'subject_group_id']);
    }

    public function getExpertises()
    {
        return $this->hasMany(Expertise::class, ['id' => 'expertise_id'])
            ->viaTable('{{%personnel_expertises}}', ['personnel_id' => 'id']);
    }

    public function getCertifications()
    {
        return $this->hasMany(Certification::class, ['personnel_id' => 'id']);
    }

    public static function getGenderList()
    {
        return [
            'male' => 'ชาย',
            'female' => 'หญิง',
        ];
    }

    public static function getTrackList()
    {
        return [
            'สาย ป' => 'สาย ป (ปฏิบัติการ)',
            'สาย ว' => 'สาย ว (วิชาการ)',
        ];
    }

    public static function getAcademicPositionList()
    {
        return [
            'ผศ.' => 'ผศ. (ผู้ช่วยศาสตราจารย์)',
            'รศ.' => 'รศ. (รองศาสตราจารย์)',
            'ศ.' => 'ศ. (ศาสตราจารย์)',
            'ศาสตราภิชาน' => 'ศาสตราภิชาน',
        ];
    }

    public static function getJobPositionList()
    {
        return [
            'อาจารย์' => 'อาจารย์',
            'นักวิชาการ' => 'นักวิชาการ',
            'เจ้าหน้าที่บริหารงานทั่วไป' => 'เจ้าหน้าที่บริหารงานทั่วไป',
        ];
    }
}

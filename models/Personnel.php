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
            [['fullname'], 'string', 'max' => 255],
            [['start_date', 'contract_end_date', 'birth_date'], 'safe'],
            [['photo'], 'string', 'max' => 500],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['status'], 'integer'],
            [['qualification_id', 'contract_type_id', 'department_id'], 'integer'],
            [['qualification_id'], 'exist', 'targetClass' => Qualification::class, 'targetAttribute' => 'id'],
            [['contract_type_id'], 'exist', 'targetClass' => ContractType::class, 'targetAttribute' => 'id'],
            [['department_id'], 'exist', 'targetClass' => Department::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'personnel_code' => 'รหัสบุคลากร',
            'gender' => 'เพศ',
            'fullname' => 'ชื่อ-นามสกุล',
            'start_date' => 'วันเริ่มงาน',
            'contract_end_date' => 'วันสิ้นสุดสัญญา',
            'birth_date' => 'วันเกิด',
            'photo' => 'รูปภาพ',
            'phone' => 'โทรศัพท์',
            'email' => 'อีเมล',
            'status' => 'สถานะ',
            'qualification_id' => 'คุณวุฒิ',
            'contract_type_id' => 'ประเภทสัญญา',
            'department_id' => 'แผนก',
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
}

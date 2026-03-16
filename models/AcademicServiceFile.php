<?php

namespace app\models;

use yii\db\ActiveRecord;

class AcademicServiceFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%academic_service_files}}';
    }

    public function rules()
    {
        return [
            [['academic_service_id', 'file_path'], 'required'],
            [['academic_service_id'], 'integer'],
            [['file_path'], 'string', 'max' => 500],
            [['file_type'], 'string', 'max' => 20],
            [['original_name'], 'string', 'max' => 255],
            [['file_type'], 'default', 'value' => 'attachment'],
            [['academic_service_id'], 'exist', 'targetClass' => AcademicService::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'academic_service_id' => 'บริการวิชาการ/ทำนุบำรุงวัฒนธรรม',
            'file_path' => 'ไฟล์',
            'file_type' => 'ประเภทไฟล์',
            'original_name' => 'ชื่อไฟล์',
        ];
    }

    public function getAcademicService()
    {
        return $this->hasOne(AcademicService::class, ['id' => 'academic_service_id']);
    }
}

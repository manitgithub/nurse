<?php

namespace app\models;

use yii\db\ActiveRecord;

class Certification extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certifications}}';
    }

    public function rules()
    {
        return [
            [['personnel_id'], 'required'],
            [['personnel_id', 'certification_level_id'], 'integer'],
            [['training_batch'], 'string', 'max' => 100],
            [['certified_date'], 'safe'],
            [['remark'], 'string'],
            [['personnel_id'], 'exist', 'targetClass' => Personnel::class, 'targetAttribute' => 'id'],
            [['certification_level_id'], 'exist', 'targetClass' => CertificationLevel::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'personnel_id' => 'บุคลากร',
            'certification_level_id' => 'ระดับใบรับรอง',
            'training_batch' => 'รุ่นที่อบรม',
            'certified_date' => 'วันที่ได้รับ',
            'remark' => 'หมายเหตุ',
        ];
    }

    public function getPersonnel()
    {
        return $this->hasOne(Personnel::class, ['id' => 'personnel_id']);
    }

    public function getCertificationLevel()
    {
        return $this->hasOne(CertificationLevel::class, ['id' => 'certification_level_id']);
    }
}

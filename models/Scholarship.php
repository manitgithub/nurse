<?php

namespace app\models;

use yii\db\ActiveRecord;

class Scholarship extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%scholarships}}';
    }

    public function rules()
    {
        return [
            [['academic_year', 'scholar_name'], 'required'],
            [['academic_year'], 'string', 'max' => 10],
            [['qualification_id'], 'integer'],
            [['scholar_name', 'institution', 'major'], 'string', 'max' => 255],
            [['start_date', 'end_date'], 'safe'],
            [['qualification_id'], 'exist', 'targetClass' => Qualification::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'academic_year' => 'ปีการศึกษา',
            'qualification_id' => 'ระดับคุณวุฒิ',
            'scholar_name' => 'ชื่อนักเรียนทุน',
            'institution' => 'สถาบัน',
            'major' => 'สาขา',
            'start_date' => 'วันเริ่มต้น',
            'end_date' => 'วันสิ้นสุด',
        ];
    }

    public function getQualification()
    {
        return $this->hasOne(Qualification::class, ['id' => 'qualification_id']);
    }
}

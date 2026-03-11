<?php

namespace app\models;

use yii\db\ActiveRecord;

class Innovation extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%innovations}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'advisor'], 'string', 'max' => 1000],
            [['invention_date'], 'safe'],
            [['problem', 'process', 'results', 'developers'], 'string'],
            [['latitude', 'longitude'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อนวัตกรรม',
            'invention_date' => 'ว/ด/ป',
            'problem' => 'สถานการณ์หรือปัญหาที่ก่อให้เกิดนวัตกรรม',
            'process' => 'กระบวนการนวัตกรรม',
            'results' => 'ผลลัพธ์/การนำไปใช้',
            'advisor' => 'อาจารย์ที่ปรึกษา',
            'developers' => 'ผู้พัฒนานวัตกรรม',
            'latitude' => 'ละติจูด',
            'longitude' => 'ลองจิจูด',
        ];
    }

    public function getFiles()
    {
        return $this->hasMany(InnovationFile::class, ['innovation_id' => 'id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(InnovationFile::class, ['innovation_id' => 'id'])
            ->andWhere(['file_type' => 'attachment']);
    }

    public function getImages()
    {
        return $this->hasMany(InnovationFile::class, ['innovation_id' => 'id'])
            ->andWhere(['file_type' => 'image']);
    }
}

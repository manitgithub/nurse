<?php

namespace app\models;

use yii\db\ActiveRecord;

class Research extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%researches}}';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 1000],
            [['year'], 'string', 'max' => 10],
            [['work_status', 'funding_status', 'publish_level'], 'string', 'max' => 100],
            [['authors', 'funding_source'], 'string', 'max' => 500],
            [['duration', 'tier'], 'string', 'max' => 255],
            [['result_publication'], 'string'],
            [['budget', 'latitude', 'longitude'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'ชื่อผลงาน',
            'year' => 'พ.ศ.',
            'work_status' => 'สถานะผลงาน',
            'authors' => 'ชื่อเจ้าผลงาน',
            'funding_status' => 'สถานะแหล่งทุน',
            'funding_source' => 'ชื่อแหล่งทุน',
            'budget' => 'งบประมาณ',
            'duration' => 'ระยะเวลาดำเนินการ',
            'publish_level' => 'เผยแพร่ระดับ',
            'tier' => 'ระดับชั้น',
            'result_publication' => 'ผลการดำเนินการ/แหล่งเผยแพร่',
            'latitude' => 'ละติจูด',
            'longitude' => 'ลองจิจูด',
        ];
    }

    public function getFiles()
    {
        return $this->hasMany(ResearchFile::class, ['research_id' => 'id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(ResearchFile::class, ['research_id' => 'id'])
            ->andWhere(['file_type' => 'attachment']);
    }

    public function getImages()
    {
        return $this->hasMany(ResearchFile::class, ['research_id' => 'id'])
            ->andWhere(['file_type' => 'image']);
    }
}

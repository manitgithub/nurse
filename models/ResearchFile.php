<?php

namespace app\models;

use yii\db\ActiveRecord;

class ResearchFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%research_files}}';
    }

    public function rules()
    {
        return [
            [['research_id', 'file_path'], 'required'],
            [['research_id'], 'integer'],
            [['file_path'], 'string', 'max' => 500],
            [['file_type'], 'string', 'max' => 20],
            [['original_name'], 'string', 'max' => 255],
            [['file_type'], 'default', 'value' => 'attachment'],
            [['research_id'], 'exist', 'targetClass' => Research::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'research_id' => 'งานวิจัย',
            'file_path' => 'ไฟล์',
            'file_type' => 'ประเภทไฟล์',
            'original_name' => 'ชื่อไฟล์',
        ];
    }

    public function getResearch()
    {
        return $this->hasOne(Research::class, ['id' => 'research_id']);
    }
}

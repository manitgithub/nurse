<?php

namespace app\models;

use yii\db\ActiveRecord;

class InnovationFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%innovation_files}}';
    }

    public function rules()
    {
        return [
            [['innovation_id', 'file_path'], 'required'],
            [['innovation_id'], 'integer'],
            [['file_path'], 'string', 'max' => 500],
            [['file_type'], 'string', 'max' => 20],
            [['original_name'], 'string', 'max' => 255],
            [['file_type'], 'default', 'value' => 'attachment'],
            [['innovation_id'], 'exist', 'targetClass' => Innovation::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'innovation_id' => 'นวัตกรรม',
            'file_path' => 'ไฟล์',
            'file_type' => 'ประเภทไฟล์',
            'original_name' => 'ชื่อไฟล์',
        ];
    }

    public function getInnovation()
    {
        return $this->hasOne(Innovation::class, ['id' => 'innovation_id']);
    }
}

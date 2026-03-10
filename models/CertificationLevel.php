<?php

namespace app\models;

use yii\db\ActiveRecord;

class CertificationLevel extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certification_levels}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['status'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ระดับใบรับรอง',
            'status' => 'สถานะ',
        ];
    }
}

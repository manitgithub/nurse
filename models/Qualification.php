<?php

namespace app\models;

use yii\db\ActiveRecord;

class Qualification extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%qualifications}}';
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
            'name' => 'ชื่อคุณวุฒิ',
            'status' => 'สถานะ',
        ];
    }
}

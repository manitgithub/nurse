<?php

namespace app\models;

use yii\db\ActiveRecord;

class Department extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%departments}}';
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
            'name' => 'ชื่อสาขา',
            'status' => 'สถานะ',
        ];
    }
}

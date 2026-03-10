<?php

namespace app\models;

use yii\db\ActiveRecord;

class ContractType extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%contract_types}}';
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
            'name' => 'ประเภทสัญญา',
            'status' => 'สถานะ',
        ];
    }
}

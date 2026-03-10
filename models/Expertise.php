<?php

namespace app\models;

use yii\db\ActiveRecord;

class Expertise extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%expertises}}';
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
            'name' => 'ความเชี่ยวชาญ',
            'status' => 'สถานะ',
        ];
    }

    public function getPersonnels()
    {
        return $this->hasMany(Personnel::class, ['id' => 'personnel_id'])
            ->viaTable('{{%personnel_expertises}}', ['expertise_id' => 'id']);
    }
}

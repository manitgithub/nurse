<?php

namespace app\models;

use yii\db\ActiveRecord;

class PersonnelExpertise extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%personnel_expertises}}';
    }

    public function rules()
    {
        return [
            [['personnel_id', 'expertise_id'], 'required'],
            [['personnel_id', 'expertise_id'], 'integer'],
            [['personnel_id'], 'exist', 'targetClass' => Personnel::class, 'targetAttribute' => 'id'],
            [['expertise_id'], 'exist', 'targetClass' => Expertise::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'personnel_id' => 'บุคลากร',
            'expertise_id' => 'ความเชี่ยวชาญ',
        ];
    }

    public function getPersonnel()
    {
        return $this->hasOne(Personnel::class, ['id' => 'personnel_id']);
    }

    public function getExpertise()
    {
        return $this->hasOne(Expertise::class, ['id' => 'expertise_id']);
    }
}

<?php

namespace app\models;

use yii\db\ActiveRecord;

class ExamRound extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%exam_rounds}}';
    }

    public function rules()
    {
        return [
            [['year', 'round_number'], 'required'],
            [['year', 'round_number'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'ปี พ.ศ.',
            'round_number' => 'รอบที่',
        ];
    }

    public function getExamResults()
    {
        return $this->hasMany(ExamResult::class, ['round_id' => 'id']);
    }

    public function getDisplayName()
    {
        return "ปี {$this->year} รอบที่ {$this->round_number}";
    }
}

<?php

namespace app\models;

use yii\db\ActiveRecord;

class AcademicRecruitmentPlan extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%academic_recruitment_plans}}';
    }

    public function rules()
    {
        return [
            [['fiscal_year'], 'required'],
            [['fiscal_year'], 'string', 'max' => 10],
            [['quota_amount', 'recruited_amount'], 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fiscal_year' => 'ปีงบประมาณ',
            'quota_amount' => 'อัตราที่ได้รับ',
            'recruited_amount' => 'อัตราที่บรรจุแล้ว',
        ];
    }

    /**
     * คำนวณอัตราคงเหลือ
     */
    public function getRemainingQuota()
    {
        return $this->quota_amount - $this->recruited_amount;
    }
}

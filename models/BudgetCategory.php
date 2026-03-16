<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "budget_categories".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BudgetAllocation[] $allocations
 */
class BudgetCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%budget_categories}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'หมวดกิจกรรม',
            'status' => 'สถานะ',
            'created_at' => 'สร้างเมื่อ',
            'updated_at' => 'แก้ไขเมื่อ',
        ];
    }

    public function getAllocations()
    {
        return $this->hasMany(BudgetAllocation::class, ['category_id' => 'id']);
    }
}

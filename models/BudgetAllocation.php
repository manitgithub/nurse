<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "budget_allocations".
 *
 * @property int $id
 * @property int $category_id
 * @property string $fiscal_year
 * @property float $allocated_amount
 * @property float $adjustment_reduction
 * @property float $adjustment_addition
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BudgetCategory $category
 * @property BudgetTransaction[] $transactions
 */
class BudgetAllocation extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%budget_allocations}}';
    }

    public function rules()
    {
        return [
            [['category_id', 'fiscal_year'], 'required'],
            [['category_id'], 'integer'],
            [['allocated_amount', 'adjustment_reduction', 'adjustment_addition'], 'number'],
            [['fiscal_year'], 'string', 'max' => 10],
            [['created_at', 'updated_at'], 'safe'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BudgetCategory::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'หมวดกิจกรรม',
            'fiscal_year' => 'ประจำปีงบประมาณ',
            'allocated_amount' => 'จำนวนเงินได้รับจัดสรร',
            'adjustment_reduction' => 'ปรับลดระหว่างปี',
            'adjustment_addition' => 'รับเพิ่มระหว่างปี',
            'created_at' => 'สร้างเมื่อ',
            'updated_at' => 'แก้ไขเมื่อ',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(BudgetCategory::class, ['id' => 'category_id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(BudgetTransaction::class, ['allocation_id' => 'id']);
    }

    public function getTotalBudget()
    {
        return (float)$this->allocated_amount - (float)$this->adjustment_reduction + (float)$this->adjustment_addition;
    }

    public function getTotalExpenses()
    {
        return (float) $this->getTransactions()->sum('COALESCE(cost_compensation, 0) + COALESCE(cost_accommodation, 0) + COALESCE(cost_materials, 0) + COALESCE(cost_hospitality, 0) + COALESCE(cost_transportation, 0)');
    }

    public function getRemainingBalance()
    {
        return $this->getTotalBudget() - $this->getTotalExpenses();
    }
}

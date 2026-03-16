<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "budget_transactions".
 *
 * @property int $id
 * @property int $allocation_id
 * @property string $transaction_date
 * @property string $activity_name
 * @property string $requester
 * @property float $proposed_amount
 * @property float $cost_compensation
 * @property float $cost_accommodation
 * @property float $cost_materials
 * @property float $cost_hospitality
 * @property float $cost_transportation
 * @property string $reference_no
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BudgetAllocation $allocation
 */
class BudgetTransaction extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%budget_transactions}}';
    }

    public function rules()
    {
        return [
            [['allocation_id', 'transaction_date', 'activity_name'], 'required'],
            [['allocation_id'], 'integer'],
            [['transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['proposed_amount', 'cost_compensation', 'cost_accommodation', 'cost_materials', 'cost_hospitality', 'cost_transportation'], 'number'],
            [['note'], 'string'],
            [['activity_name', 'subject_name'], 'string', 'max' => 500],
            [['requester'], 'string', 'max' => 255],
            [['reference_no'], 'string', 'max' => 100],
            [['allocation_id'], 'exist', 'skipOnError' => true, 'targetClass' => BudgetAllocation::class, 'targetAttribute' => ['allocation_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'allocation_id' => 'การจัดสรรงบประมาณ',
            'transaction_date' => 'วดป.',
            'activity_name' => 'กิจกรรม',
            'subject_name' => 'รายวิชา',
            'requester' => 'ผู้เบิก',
            'proposed_amount' => 'เสนอขออนุมัติ',
            'cost_compensation' => 'ค่าตอบแทน/ค่าจ้าง',
            'cost_accommodation' => 'ค่าที่พัก',
            'cost_materials' => 'ค่าวัสดุ/ใช้สอยอื่น',
            'cost_hospitality' => 'ค่ารับรอง/ค่าเบี้ยเลี้ยง',
            'cost_transportation' => 'ค่าพาหนะ',
            'reference_no' => 'เลขที่อ้างอิง',
            'note' => 'หมายเหตุ',
            'created_at' => 'สร้างเมื่อ',
            'updated_at' => 'แก้ไขเมื่อ',
        ];
    }

    public function getAllocation()
    {
        return $this->hasOne(BudgetAllocation::class, ['id' => 'allocation_id']);
    }

    public function getTotalCost()
    {
        return (float)$this->cost_compensation + (float)$this->cost_accommodation + (float)$this->cost_materials + (float)$this->cost_hospitality + (float)$this->cost_transportation;
    }

    public function getBalance()
    {
        return (float)$this->proposed_amount - $this->getTotalCost();
    }
}

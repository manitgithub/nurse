<?php

use yii\db\Migration;

/**
 * Class m260313_084600_seed_budget_transactions_15_2569
 */
class m260313_084600_seed_budget_transactions_15_2569 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%budget_transactions}}', ['allocation_id', 'transaction_date', 'activity_name', 'requester', 'proposed_amount', 'cost_compensation', 'cost_accommodation', 'cost_materials', 'cost_hospitality', 'cost_transportation', 'reference_no'], [
            [15, '2025-10-16', 'ค่าอาหารประชุม PLO', 'ธีรนันท์', 740.0, 0.0, 0.0, 0.0, 740.0, 0.0, '1433/69'],
            [15, '2025-10-30', 'ค่ารับรองประชุมขับเคลื่อน กลยุทธ์ 20 ตค 68', 'รัตนากร', 978.0, 0.0, 0.0, 0.0, 978.0, 0.0, '1504/69'],
            [15, '2025-10-30', 'ค่ารับรองประชุมกรรมการประเมิน 9 ตค 68', 'รัตนากร', 3250.0, 0.0, 0.0, 0.0, 3250.0, 0.0, '1503/69'],
            [15, '2025-10-31', 'ค่ารับรองประชุมบอร์ด', 'วรรณา', 2348.0, 0.0, 0.0, 0.0, 2348.0, 0.0, '1528/69'],
            [15, '2026-01-07', 'ค่าล่วงเวลา', 'วรรณา', 450.0, 0.0, 0.0, 0.0, 450.0, 0.0, '2569/0575'],
            [15, '2026-01-07', 'ค่าล่วงเวลา', 'รัตนากร', 550.0, 0.0, 0.0, 0.0, 550.0, 0.0, null],
            [15, '2026-01-07', 'ค่าล่วงเวลา', 'องค์อร', 600.0, 0.0, 0.0, 0.0, 600.0, 0.0, null],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%budget_transactions}}', ['allocation_id' => 15]);
    }
}

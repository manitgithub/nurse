<?php

use yii\db\Migration;

/**
 * Class m260313_083300_seed_budget_transactions_13_2569
 */
class m260313_083300_seed_budget_transactions_13_2569 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%budget_transactions}}', ['allocation_id', 'transaction_date', 'activity_name', 'requester', 'proposed_amount', 'cost_compensation', 'cost_accommodation', 'cost_materials', 'cost_hospitality', 'cost_transportation', 'reference_no'], [
            [13, '2025-12-24', 'ค่าตอบแทนกรรมการสอบ วิทยานิพนธ์ -โยธกา', '', 2000.0, 2000.0, 0.0, 0.0, 0.0, 0.0, null],
            [13, '2025-12-29', 'ค่าตอบแทนกรรมการสอบ วิทยานิพนธ์-วาสนา', '', 2000.0, 2000.0, 0.0, 0.0, 0.0, 0.0, null],
            [13, '2026-01-09', 'ค่าตอบแทนกรรมการสอบ วิภาดา', '', 2000.0, 2000.0, 0.0, 0.0, 0.0, 0.0, '68/69'],
            [13, '2026-01-13', 'ค่าตอบแทนกรรมการสอบ วริสา', '', 2000.0, 2000.0, 0.0, 0.0, 0.0, 0.0, '77/69'],
            [13, '2026-01-29', 'ค่าตอบแทนกรรมการสอบ ปราถนา อุสมาน วัชรินทร์', '', 3000.0, 3000.0, 0.0, 0.0, 0.0, 0.0, '190/69'],
            [13, '2026-02-17', 'ค่าตอบแทนกรรมการสอบ เพ็ญนภา วริสรา', '', 3000.0, 3000.0, 0.0, 0.0, 0.0, 0.0, '251/69'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%budget_transactions}}', ['allocation_id' => 13]);
    }
}

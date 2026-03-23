<?php

use yii\db\Migration;

/**
 * Class m260318_092801_import_budget_data_2567
 */
class m260318_092801_import_budget_data_2567 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $fiscalYear = '2567';
        $data = [
            ['category_id' => 1, 'allocated' => 350000.00, 'reduction' => 30000.00, 'addition' => 300000.00, 'expense' => 549501.95],
            ['category_id' => 2, 'allocated' => 32000.00, 'reduction' => 0.00, 'addition' => 0.00, 'expense' => 31999.90],
            ['category_id' => 3, 'allocated' => 3650000.00, 'reduction' => 1450000.00, 'addition' => 0.00, 'expense' => 2142941.35],
            ['category_id' => 4, 'allocated' => 250000.00, 'reduction' => 50000.00, 'addition' => 0.00, 'expense' => 184110.00],
            ['category_id' => 5, 'allocated' => 87000.00, 'reduction' => 0.00, 'addition' => 0.00, 'expense' => 86999.40],
            ['category_id' => 6, 'allocated' => 50000.00, 'reduction' => 0.00, 'addition' => 0.00, 'expense' => 49961.70],
            ['category_id' => 7, 'allocated' => 10000.00, 'reduction' => 0.00, 'addition' => 0.00, 'expense' => 0.00],
            ['category_id' => 8, 'allocated' => 5000.00, 'reduction' => 0.00, 'addition' => 0.00, 'expense' => 4966.56],
        ];

        foreach ($data as $item) {
            // 1. Insert Allocation
            $this->insert('{{%budget_allocations}}', [
                'category_id' => $item['category_id'],
                'fiscal_year' => $fiscalYear,
                'allocated_amount' => $item['allocated'],
                'adjustment_reduction' => $item['reduction'],
                'adjustment_addition' => $item['addition'],
            ]);

            $allocationId = $this->db->getLastInsertID();

            // 2. Insert Summary Transaction (Expense)
            $this->insert('{{%budget_transactions}}', [
                'allocation_id' => $allocationId,
                'transaction_date' => '2024-12-31', // Assuming end of year 2567 (which is 2024 AD)
                'activity_name' => 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)',
                'proposed_amount' => $item['expense'],
                'requester' => 'System',
                'note' => 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567',
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Find allocations for 2567 and delete them (and their transactions due to CASCADE)
        $this->delete('{{%budget_allocations}}', ['fiscal_year' => '2567']);
    }
}

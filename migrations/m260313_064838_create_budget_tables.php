<?php

use yii\db\Migration;

class m260313_064838_create_budget_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Budget Categories
        $this->createTable('{{%budget_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // 2. Budget Allocations (Income/Budget for Year)
        $this->createTable('{{%budget_allocations}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'fiscal_year' => $this->string(10)->notNull(),
            'allocated_amount' => $this->decimal(15, 2)->defaultValue(0),
            'adjustment_reduction' => $this->decimal(15, 2)->defaultValue(0),
            'adjustment_addition' => $this->decimal(15, 2)->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-budget_allocations-category_id', '{{%budget_allocations}}', 'category_id', '{{%budget_categories}}', 'id', 'CASCADE');

        // 3. Budget Transactions (Expenses)
        $this->createTable('{{%budget_transactions}}', [
            'id' => $this->primaryKey(),
            'allocation_id' => $this->integer()->notNull(),
            'transaction_date' => $this->date()->notNull(),
            'activity_name' => $this->string(500)->notNull(),
            'requester' => $this->string(255),
            'proposed_amount' => $this->decimal(15, 2)->defaultValue(0),
            'cost_compensation' => $this->decimal(15, 2)->defaultValue(0),
            'cost_accommodation' => $this->decimal(15, 2)->defaultValue(0),
            'cost_materials' => $this->decimal(15, 2)->defaultValue(0),
            'cost_hospitality' => $this->decimal(15, 2)->defaultValue(0),
            'cost_transportation' => $this->decimal(15, 2)->defaultValue(0),
            'reference_no' => $this->string(100),
            'note' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-budget_transactions-allocation_id', '{{%budget_transactions}}', 'allocation_id', '{{%budget_allocations}}', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-budget_transactions-allocation_id', '{{%budget_transactions}}');
        $this->dropTable('{{%budget_transactions}}');
        $this->dropForeignKey('fk-budget_allocations-category_id', '{{%budget_allocations}}');
        $this->dropTable('{{%budget_allocations}}');
        $this->dropTable('{{%budget_categories}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260313_064838_create_budget_tables cannot be reverted.\n";

        return false;
    }
    */
}

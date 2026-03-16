<?php

use yii\db\Migration;

class m260313_073111_add_subject_name_to_budget_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%budget_transactions}}', 'subject_name', $this->string(500)->after('activity_name'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%budget_transactions}}', 'subject_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260313_073111_add_subject_name_to_budget_transactions cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

class m260313_080719_fix_subject_name_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add the column if it doesn't exist (safety check since the previous migration might have failed silently or partially)
        $table = Yii::$app->db->getTableSchema('{{%budget_transactions}}');
        if (!isset($table->columns['subject_name'])) {
            $this->addColumn('{{%budget_transactions}}', 'subject_name', $this->string(500)->after('activity_name'));
        }
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
        echo "m260313_080719_fix_subject_name_column cannot be reverted.\n";

        return false;
    }
    */
}

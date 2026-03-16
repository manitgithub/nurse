<?php

use yii\db\Migration;

class m260316_030252_add_advisor_id_to_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%students}}', 'advisor_id', $this->integer()->after('status'));
        
        // Add foreign key for advisor (Personnel)
        $this->addForeignKey(
            'fk-student-advisor_id',
            '{{%students}}',
            'advisor_id',
            '{{%personnels}}',
            'id',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-student-advisor_id', '{{%students}}');
        $this->dropColumn('{{%students}}', 'advisor_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260316_030252_add_advisor_id_to_students cannot be reverted.\n";

        return false;
    }
    */
}

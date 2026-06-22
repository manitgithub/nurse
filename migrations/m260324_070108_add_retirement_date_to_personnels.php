<?php

use yii\db\Migration;

class m260324_070108_add_retirement_date_to_personnels extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%personnels}}', 'retirement_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%personnels}}', 'retirement_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260324_070108_add_retirement_date_to_personnels cannot be reverted.\n";

        return false;
    }
    */
}

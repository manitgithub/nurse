<?php

use yii\db\Migration;

class m260324_070108_add_retirement_date_to_personnels extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m260324_070108_add_retirement_date_to_personnels cannot be reverted.\n";

        return false;
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

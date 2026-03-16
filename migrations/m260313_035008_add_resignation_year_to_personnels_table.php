<?php

use yii\db\Migration;

/**
 * Class m260313_035008_add_resignation_year_to_personnels_table
 */
class m260313_035008_add_resignation_year_to_personnels_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%personnels}}', 'resignation_year', $this->string(4)->comment('ปีที่ลาออก'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%personnels}}', 'resignation_year');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260313_035008_add_resignation_year_to_personnels_table cannot be reverted.\n";

        return false;
    }
    */
}

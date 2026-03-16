<?php

use yii\db\Migration;

/**
 * Class m260313_041039_add_subject_group_id_to_personnels_table
 */
class m260313_041039_add_subject_group_id_to_personnels_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%personnels}}', 'subject_group_id', $this->integer()->comment('สาขาตามโครงสร้าง'));

        $this->addForeignKey(
            'fk-personnels-subject_group_id',
            '{{%personnels}}',
            'subject_group_id',
            '{{%subject_groups}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-personnels-subject_group_id', '{{%personnels}}');
        $this->dropColumn('{{%personnels}}', 'subject_group_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260313_041039_add_subject_group_id_to_personnels_table cannot be reverted.\n";

        return false;
    }
    */
}

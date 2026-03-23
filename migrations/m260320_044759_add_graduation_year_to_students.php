<?php

use yii\db\Migration;

class m260320_044759_add_graduation_year_to_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%students}}', 'graduation_year', $this->integer()->after('status')->comment('ปีที่จบการศึกษา (พ.ศ.)'));
        $this->createIndex('idx-students-graduation_year', '{{%students}}', 'graduation_year');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-students-graduation_year', '{{%students}}');
        $this->dropColumn('{{%students}}', 'graduation_year');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260320_044759_add_graduation_year_to_students cannot be reverted.\n";

        return false;
    }
    */
}

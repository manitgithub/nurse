<?php

use yii\db\Migration;

class m260310_050000_add_batch_to_students extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%students}}', 'batch', $this->string(10)->after('student_id')->comment('รุ่น เช่น 69'));
        $this->createIndex('idx-students-batch', '{{%students}}', 'batch');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-students-batch', '{{%students}}');
        $this->dropColumn('{{%students}}', 'batch');
    }
}

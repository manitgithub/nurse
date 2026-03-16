<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subject_groups}}`.
 */
class m260313_041018_create_subject_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subject_groups}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('ชื่อสาขาตามโครงสร้าง'),
            'status' => $this->boolean()->defaultValue(1)->comment('สถานะ (1=ใช้งาน, 0=ยกเลิก)'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subject_groups}}');
    }
}

<?php

use yii\db\Migration;

class m260310_040012_create_certifications_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%certifications}}', [
            'id' => $this->primaryKey(),
            'personnel_id' => $this->integer()->notNull(),
            'certification_level_id' => $this->integer(),
            'training_batch' => $this->string(100)->comment('รหัสที่อบรม'),
            'certified_date' => $this->date(),
            'remark' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey('fk_certifications_personnel', '{{%certifications}}', 'personnel_id', '{{%personnels}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_certifications_level', '{{%certifications}}', 'certification_level_id', '{{%certification_levels}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%certifications}}');
    }
}

<?php

use yii\db\Migration;

class m260310_040014_create_academic_recruitment_plans_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%academic_recruitment_plans}}', [
            'id' => $this->primaryKey(),
            'fiscal_year' => $this->string(10)->notNull()->comment('ปีงบประมาณ'),
            'quota_amount' => $this->integer()->defaultValue(0)->comment('อัตราที่ได้รับ'),
            'recruited_amount' => $this->integer()->defaultValue(0)->comment('อัตราที่บรรจุแล้ว'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function safeDown()
    {
        $this->dropTable('{{%academic_recruitment_plans}}');
    }
}

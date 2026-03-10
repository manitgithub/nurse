<?php

use yii\db\Migration;

class m260310_040009_create_personnels_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%personnels}}', [
            'id' => $this->primaryKey(),
            'personnel_code' => $this->string(50)->notNull()->unique(),
            'gender' => $this->string(10),
            'fullname' => $this->string(255)->notNull(),
            'start_date' => $this->date(),
            'contract_end_date' => $this->date(),
            'birth_date' => $this->date(),
            'photo' => $this->string(500)->comment('Path to photo file'),
            'phone' => $this->string(20),
            'email' => $this->string(255),
            'status' => $this->tinyInteger()->defaultValue(1)->comment('1=Active, 0=Inactive'),
            'qualification_id' => $this->integer(),
            'contract_type_id' => $this->integer(),
            'department_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey('fk_personnels_qualification', '{{%personnels}}', 'qualification_id', '{{%qualifications}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_personnels_contract_type', '{{%personnels}}', 'contract_type_id', '{{%contract_types}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_personnels_department', '{{%personnels}}', 'department_id', '{{%departments}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%personnels}}');
    }
}

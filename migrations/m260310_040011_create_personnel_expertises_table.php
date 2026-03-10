<?php

use yii\db\Migration;

class m260310_040011_create_personnel_expertises_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%personnel_expertises}}', [
            'personnel_id' => $this->integer()->notNull(),
            'expertise_id' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addPrimaryKey('pk_personnel_expertises', '{{%personnel_expertises}}', ['personnel_id', 'expertise_id']);

        $this->addForeignKey('fk_pe_personnel', '{{%personnel_expertises}}', 'personnel_id', '{{%personnels}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_pe_expertise', '{{%personnel_expertises}}', 'expertise_id', '{{%expertises}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%personnel_expertises}}');
    }
}

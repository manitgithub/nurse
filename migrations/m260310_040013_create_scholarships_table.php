<?php

use yii\db\Migration;

class m260310_040013_create_scholarships_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%scholarships}}', [
            'id' => $this->primaryKey(),
            'academic_year' => $this->string(10)->notNull()->comment('ปีการศึกษา'),
            'qualification_id' => $this->integer(),
            'scholar_name' => $this->string(255)->notNull()->comment('ชื่อนักเรียนทุน (ไม่ผูก FK)'),
            'institution' => $this->string(255)->comment('สถาบัน'),
            'major' => $this->string(255)->comment('สาขา'),
            'start_date' => $this->date(),
            'end_date' => $this->date(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey('fk_scholarships_qualification', '{{%scholarships}}', 'qualification_id', '{{%qualifications}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%scholarships}}');
    }
}

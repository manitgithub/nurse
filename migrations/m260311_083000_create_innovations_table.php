<?php

use yii\db\Migration;

class m260311_083000_create_innovations_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%innovations}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(1000)->notNull()->comment('ชื่อนวัตกรรม'),
            'invention_date' => $this->date()->comment('ว/ด/ป'),
            'problem' => $this->text()->comment('สถานการณ์หรือปัญหาที่ก่อให้เกิดนวัตกรรม'),
            'process' => $this->text()->comment('กระบวนการนวัตกรรม'),
            'results' => $this->text()->comment('ผลลัพธ์/การนำไปใช้'),
            'advisor' => $this->string(500)->comment('อาจารย์ที่ปรึกษา'),
            'developers' => $this->text()->comment('ผู้พัฒนานวัตกรรม'),
            'latitude' => $this->decimal(10, 8),
            'longitude' => $this->decimal(11, 8),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->createTable('{{%innovation_files}}', [
            'id' => $this->primaryKey(),
            'innovation_id' => $this->integer()->notNull(),
            'file_path' => $this->string(500)->notNull(),
            'file_type' => $this->string(20)->notNull()->defaultValue('attachment'),
            'original_name' => $this->string(255),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey(
            'fk_innovation_files_innovation',
            '{{%innovation_files}}',
            'innovation_id',
            '{{%innovations}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_innovation_files_innovation', '{{%innovation_files}}');
        $this->dropTable('{{%innovation_files}}');
        $this->dropTable('{{%innovations}}');
    }
}

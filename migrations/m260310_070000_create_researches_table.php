<?php

use yii\db\Migration;

class m260310_070000_create_researches_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%researches}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(1000)->notNull()->comment('ชื่อผลงาน'),
            'year' => $this->string(10)->comment('พ.ศ.'),
            'work_status' => $this->string(100)->comment('สถานะผลงาน เช่น ตีพิมพ์'),
            'authors' => $this->string(500)->comment('ชื่อเจ้าผลงาน'),
            'funding_status' => $this->string(100)->comment('สถานะผลงาน ภายใน/ภายนอก'),
            'funding_source' => $this->string(500)->comment('ชื่อแหล่งทุน'),
            'budget' => $this->decimal(12, 2)->comment('งบประมาณ'),
            'duration' => $this->string(255)->comment('ระยะเวลาดำเนินการ'),
            'publish_level' => $this->string(100)->comment('เผยแพร่ระดับ'),
            'tier' => $this->string(255)->comment('ระดับชั้น'),
            'result_publication' => $this->text()->comment('ผลการดำเนินการ/แหล่งเผยแพร่'),
            'latitude' => $this->decimal(10, 7)->comment('พิกัด lat'),
            'longitude' => $this->decimal(10, 7)->comment('พิกัด lng'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->createTable('{{%research_files}}', [
            'id' => $this->primaryKey(),
            'research_id' => $this->integer()->notNull(),
            'file_path' => $this->string(500)->notNull()->comment('path ไฟล์'),
            'file_type' => $this->string(20)->notNull()->defaultValue('attachment')->comment('attachment หรือ image'),
            'original_name' => $this->string(255)->comment('ชื่อไฟล์ดั้งเดิม'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey(
            'fk_research_files_research',
            '{{%research_files}}',
            'research_id',
            '{{%researches}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%research_files}}');
        $this->dropTable('{{%researches}}');
    }
}

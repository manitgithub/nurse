<?php

use yii\db\Migration;

class m260310_060000_create_academic_services_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%academic_services}}', [
            'id' => $this->primaryKey(),
            'activity_name' => $this->string(500)->notNull()->comment('กิจกรรม'),
            'fiscal_year' => $this->string(10)->comment('ประจำปีงบประมาณ'),
            'strategic_goal' => $this->text()->comment('เป้าหมายผลสัมฤทธิ์เชิงยุทธศาสตร์'),
            'strategy_link' => $this->text()->comment('ความเชื่อมโยง/ตอบสนองยุทธศาสตร์'),
            'project_focus' => $this->string(500)->comment('ประเด็นมุ่งเน้นของโครงการ'),
            'project_type' => $this->string(100)->comment('ลักษณะโครงการ'),
            'budget_source' => $this->string(100)->comment('แหล่งงบประมาณ'),
            'budget_category' => $this->string(255)->comment('หมวดงบประมาณที่ใช้'),
            'budget_amount' => $this->decimal(12, 2)->comment('ค่าใช้จ่ายในโครงการ (บาท)'),
            'start_date' => $this->date()->comment('วันที่เริ่ม'),
            'end_date' => $this->date()->comment('วันที่สิ้นสุด'),
            'teacher_role' => $this->text()->comment('บทบาทของอาจารย์'),
            'responsible_person' => $this->string(255)->comment('ผู้รับผิดชอบโครงการ'),
            'target_group' => $this->text()->comment('กลุ่มผู้รับบริการ / พื้นที่จัดกิจกรรม'),
            'result_percentage' => $this->text()->comment('ผลการดำเนินงาน สรุปผลเป็น %'),
            'participants_count' => $this->integer()->comment('จำนวนผู้เข้ารับบริการ (คน)'),
            'problems' => $this->text()->comment('ปัญหาอุปสรรค'),
            'solutions' => $this->text()->comment('แนวทางการแก้ไข'),
            'status' => $this->string(100)->defaultValue('กำลังดำเนินการ')->comment('สถานะ'),
            'latitude' => $this->decimal(10, 7)->comment('พิกัด lat'),
            'longitude' => $this->decimal(10, 7)->comment('พิกัด lng'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->createTable('{{%academic_service_files}}', [
            'id' => $this->primaryKey(),
            'academic_service_id' => $this->integer()->notNull(),
            'file_path' => $this->string(500)->notNull()->comment('path ไฟล์'),
            'file_type' => $this->string(20)->notNull()->defaultValue('attachment')->comment('attachment หรือ image'),
            'original_name' => $this->string(255)->comment('ชื่อไฟล์ดั้งเดิม'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey(
            'fk_academic_service_files_service',
            '{{%academic_service_files}}',
            'academic_service_id',
            '{{%academic_services}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%academic_service_files}}');
        $this->dropTable('{{%academic_services}}');
    }
}

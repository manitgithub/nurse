<?php

use yii\db\Migration;

/**
 * Class m260313_034200_add_academic_service_form_fields
 */
class m260313_034200_add_academic_service_form_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%academic_services}}', 'strategic_number', $this->string(255)->comment('ยุทธศาสตร์ที่'));
        $this->addColumn('{{%academic_services}}', 'strategic_objective', $this->string(500)->comment('เป้าประสงค์'));
        $this->addColumn('{{%academic_services}}', 'qa_indicator_1', $this->string(500)->comment('ตัวบ่งชี้ประกันคุณภาพ 1'));
        $this->addColumn('{{%academic_services}}', 'qa_indicator_2', $this->string(500)->comment('ตัวบ่งชี้ประกันคุณภาพ 2'));
        $this->addColumn('{{%academic_services}}', 'qa_indicator_3', $this->string(500)->comment('ตัวบ่งชี้ประกันคุณภาพ 3'));
        $this->addColumn('{{%academic_services}}', 'qa_indicator_4', $this->string(500)->comment('ตัวบ่งชี้ประกันคุณภาพ 4'));
        $this->addColumn('{{%academic_services}}', 'qa_indicator_5', $this->string(500)->comment('ตัวบ่งชี้ประกันคุณภาพ 5'));

        $this->addColumn('{{%academic_services}}', 'integration_teaching', $this->boolean()->defaultValue(false)->comment('บูรณาการการเรียนการสอน'));
        $this->addColumn('{{%academic_services}}', 'integration_teaching_subject', $this->string(255)->comment('รายวิชา'));
        $this->addColumn('{{%academic_services}}', 'integration_teaching_semester', $this->string(255)->comment('ภาคการศึกษาที่'));

        $this->addColumn('{{%academic_services}}', 'integration_student_activity', $this->boolean()->defaultValue(false)->comment('บูรณาการกับกิจกรรมนักศึกษา'));
        $this->addColumn('{{%academic_services}}', 'integration_student_activity_desc', $this->string(500)->comment('ระบุกิจกรรมนักศึกษา'));

        $this->addColumn('{{%academic_services}}', 'integration_academic_service', $this->boolean()->defaultValue(false)->comment('บูรณาการกับงานบริการวิชาการ'));
        $this->addColumn('{{%academic_services}}', 'integration_academic_service_desc', $this->string(500)->comment('ระบุงานบริการวิชาการ'));

        $this->addColumn('{{%academic_services}}', 'integration_research', $this->boolean()->defaultValue(false)->comment('บูรณาการงานวิจัย'));
        $this->addColumn('{{%academic_services}}', 'integration_research_desc', $this->string(500)->comment('ระบุงานวิจัย'));

        $this->addColumn('{{%academic_services}}', 'integration_other', $this->string(500)->comment('อื่นๆ ระบุ'));

        $this->addColumn('{{%academic_services}}', 'integration_problems', $this->text()->comment('ปัญหาอุปสรรค(หลังจากบูรณาการ)'));
        $this->addColumn('{{%academic_services}}', 'integration_solutions', $this->text()->comment('แนวทางการแก้ไข(หลังจากบูรณาการ)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%academic_services}}', 'strategic_number');
        $this->dropColumn('{{%academic_services}}', 'strategic_objective');
        $this->dropColumn('{{%academic_services}}', 'qa_indicator_1');
        $this->dropColumn('{{%academic_services}}', 'qa_indicator_2');
        $this->dropColumn('{{%academic_services}}', 'qa_indicator_3');
        $this->dropColumn('{{%academic_services}}', 'qa_indicator_4');
        $this->dropColumn('{{%academic_services}}', 'qa_indicator_5');

        $this->dropColumn('{{%academic_services}}', 'integration_teaching');
        $this->dropColumn('{{%academic_services}}', 'integration_teaching_subject');
        $this->dropColumn('{{%academic_services}}', 'integration_teaching_semester');

        $this->dropColumn('{{%academic_services}}', 'integration_student_activity');
        $this->dropColumn('{{%academic_services}}', 'integration_student_activity_desc');

        $this->dropColumn('{{%academic_services}}', 'integration_academic_service');
        $this->dropColumn('{{%academic_services}}', 'integration_academic_service_desc');

        $this->dropColumn('{{%academic_services}}', 'integration_research');
        $this->dropColumn('{{%academic_services}}', 'integration_research_desc');

        $this->dropColumn('{{%academic_services}}', 'integration_other');

        $this->dropColumn('{{%academic_services}}', 'integration_problems');
        $this->dropColumn('{{%academic_services}}', 'integration_solutions');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260313_034200_add_academic_service_form_fields cannot be reverted.\n";

        return false;
    }
    */
}

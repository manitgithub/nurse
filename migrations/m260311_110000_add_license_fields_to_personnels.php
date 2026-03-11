<?php

use yii\db\Migration;

class m260311_110000_add_license_fields_to_personnels extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%personnels}}', 'license_no', $this->string(100)->after('email')->comment('เลขที่ใบอนุญาตประกอบวิชาชีพ'));
        $this->addColumn('{{%personnels}}', 'council_member_no', $this->string(100)->after('license_no')->comment('เลขที่สมาชิกสภาการพยาบาล'));
        $this->addColumn('{{%personnels}}', 'license_expire_date', $this->date()->after('council_member_no')->comment('วันหมดอายุใบอนุญาตประกอบวิชาชีพ'));
        $this->addColumn('{{%personnels}}', 'license_file', $this->string(500)->after('license_expire_date')->comment('ใบอนุญาตประกอบวิชาชีพ (ไฟล์แนบ)'));
        $this->addColumn('{{%personnels}}', 'member_card_file', $this->string(500)->after('license_file')->comment('บัตรสมาชิก (ไฟล์แนบ)'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%personnels}}', 'member_card_file');
        $this->dropColumn('{{%personnels}}', 'license_file');
        $this->dropColumn('{{%personnels}}', 'license_expire_date');
        $this->dropColumn('{{%personnels}}', 'council_member_no');
        $this->dropColumn('{{%personnels}}', 'license_no');
    }
}

<?php

use yii\db\Migration;

class m260311_000000_seed_sample_data extends Migration
{
    public function safeUp()
    {
        // 1. Master Data
        $this->batchInsert('{{%qualifications}}', ['name', 'status'], [
            ['ปริญญาตรี (พยาบาลศาสตรบัณฑิต)', 1],
            ['ปริญญาโท (พยาบาลศาสตรมหาบัณฑิต)', 1],
            ['ปริญญาเอก (ปรัชญาดุษฎีบัณฑิต)', 1],
            ['ประกาศนียบัตรผู้ช่วยพยาบาล', 1],
        ]);

        $this->batchInsert('{{%contract_types}}', ['name', 'status'], [
            ['พนักงานมหาวิทยาลัย', 1],
            ['พนักงานเงินรายได้', 1],
            ['ลูกจ้างชั่วคราว', 1],
            ['ข้าราชการ', 1],
        ]);

        $this->batchInsert('{{%departments}}', ['name', 'status'], [
            ['สาขาการพยาบาลผู้ใหญ่และผู้สูงอายุ', 1],
            ['สาขาการพยาบาลเด็กและวัยรุ่น', 1],
            ['สาขาการพยาบาลมารดา ทารก และการผดุงครรภ์', 1],
            ['สาขาการพยาบาลจิตเวชและสุขภาพจิต', 1],
            ['สาขาการพยาบาลชุมชน', 1],
        ]);

        $this->batchInsert('{{%expertises}}', ['name', 'status'], [
            ['การพยาบาลผู้ป่วยวิกฤต', 1],
            ['การส่งเสริมสุขภาพผู้สูงอายุ', 1],
            ['การพยาบาลเด็กที่มีความต้องการพิเศษ', 1],
            ['การจัดการความรู้ทางการพยาบาล', 1],
        ]);

        // 2. Students
        $this->batchInsert('{{%students}}', ['student_id', 'fullname', 'high_school', 'gpax_hs', 'hometown', 'status'], [
            ['66101001', 'นายสมชาย ใจดี', 'โรงเรียนเมืองพัทลุง', 3.85, 'พัทลุง', 'active'],
            ['66101002', 'นางสาวสมศรี มีสุข', 'โรงเรียนนครศรีธรรมราชวิทยาคม', 3.92, 'นครศรีธรรมราช', 'active'],
            ['65101003', 'นายมานะ ขยันกิจ', 'โรงเรียนสุราษฎร์ธานี', 3.50, 'สุราษฎร์ธานี', 'active'],
            ['64101004', 'นางสาวสุดา อาชีพพยาบาล', 'โรงเรียนตรังวิทยาลับ', 3.75, 'ตรัง', 'graduated'],
        ]);

        // 3. Personnel
        // IDs are assumed to be 1, 2, 3 based on master data insertion above
        $this->batchInsert(
            '{{%personnels}}',
            ['personnel_code', 'fullname', 'gender', 'qualification_id', 'contract_type_id', 'department_id', 'email', 'phone', 'status'],
            [
                ['ST001', 'ดร.อรพินท์ หวานใจ', 'Female', 3, 1, 1, 'orrapin.h@university.ac.th', '081-222-3333', 1],
                ['ST002', 'ผศ.ดร.วิชัย รักเรียน', 'Male', 3, 1, 2, 'wichai.r@university.ac.th', '089-444-5555', 1],
                ['ST003', 'นางสาววิภาวดี มีเงิน', 'Female', 2, 2, 3, 'wipavadee.m@university.ac.th', '086-777-8888', 1],
            ]
        );

        // 4. Research
        $this->batchInsert(
            '{{%researches}}',
            ['title', 'year', 'work_status', 'authors', 'funding_status', 'funding_source', 'budget', 'duration', 'latitude', 'longitude'],
            [
                [
                    'การพัฒนาโมเดลการดูแลผู้สูงอายุติดเตียงในชุมชนภาคใต้',
                    '2567',
                    'ตีพิมพ์',
                    'อรพินท์ หวานใจ และคณะ',
                    'ภายนอก',
                    'สปสช.',
                    150000.00,
                    '1 ปี',
                    8.483,
                    99.963
                ],
                [
                    'ผลของโปรแกรมส่งเสริมการบริโภคอาหารในเด็กวัยเรียนที่มีภาวะโภชนาการเกิน',
                    '2566',
                    'กำลังดำเนินการ',
                    'วิชัย รักเรียน',
                    'ภายใน',
                    'งบประมาณส่วนคณะ',
                    50000.00,
                    '6 เดือน',
                    8.441,
                    100.012
                ],
            ]
        );

        // 5. Academic Services
        $this->batchInsert(
            '{{%academic_services}}',
            ['activity_name', 'fiscal_year', 'project_type', 'budget_amount', 'start_date', 'status', 'latitude', 'longitude'],
            [
                [
                    'โครงการอบรมการปฐมพยาบาลเบื้องต้นสำหรับครูโรงเรียนประถมศึกษา',
                    '2567',
                    'บริการวิชาการแก่สังคม',
                    25000.00,
                    '2024-05-15',
                    'เสร็จสิ้น',
                    8.490,
                    99.950
                ],
                [
                    'คลินิกพยาบาลชุมชนเคลื่อนที่เพื่อสุขภาพผู้สูงอายุ',
                    '2567',
                    'บริการวิชาการแบบบูรณาการ',
                    45000.00,
                    '2024-06-20',
                    'กำลังดำเนินการ',
                    8.500,
                    99.900
                ],
            ]
        );
    }

    public function safeDown()
    {
        // Delete in reverse order of dependencies
        $this->delete('{{%academic_services}}');
        $this->delete('{{%researches}}');
        $this->delete('{{%personnels}}');
        $this->delete('{{%students}}');
        $this->delete('{{%expertises}}');
        $this->delete('{{%departments}}');
        $this->delete('{{%contract_types}}');
        $this->delete('{{%qualifications}}');
    }
}

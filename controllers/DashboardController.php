<?php

namespace app\controllers;

use app\models\Student;
use app\models\StudentGrade;
use app\models\Personnel;
use app\models\Scholarship;
use app\models\AcademicRecruitmentPlan;
use app\models\ExamResult;
use app\models\LicenseExam;
use app\models\Qualification;
use app\models\Department;
use app\models\ContractType;
use app\models\CertificationLevel;
use app\models\Certification;
use app\models\Expertise;
use app\models\PersonnelExpertise;
use app\models\Research;
use app\models\AcademicService;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        // นักศึกษา - อัตราการคงอยู่
        $totalStudents = Student::find()->count();
        $activeStudents = Student::find()->where(['status' => 'active'])->count();
        $graduatedStudents = Student::find()->where(['status' => 'graduated'])->count();
        $droppedStudents = Student::find()->where(['status' => 'dropped'])->count();
        $inactiveStudents = Student::find()->where(['status' => 'inactive'])->count();

        $retentionRate = $totalStudents > 0 ? round(($activeStudents + $graduatedStudents) / $totalStudents * 100, 1) : 0;

        // บุคลากร
        $totalPersonnel = Personnel::find()->count();
        $activePersonnel = Personnel::find()->where(['status' => 1])->count();

        // นักเรียนทุน
        $totalScholarships = Scholarship::find()->count();

        // GPAX เฉลี่ยปัจจุบัน (ปีล่าสุด)
        $latestYear = StudentGrade::find()->max('academic_year');
        $avgGpax = 0;
        $gpaxByYear = [];
        if ($latestYear) {
            $avgGpax = StudentGrade::find()
                ->where(['academic_year' => $latestYear])
                ->average('gpax') ?? 0;

            // GPAX per year for chart
            $gpaxData = StudentGrade::find()
                ->select(['academic_year', 'AVG(gpax) as avg_gpax'])
                ->groupBy('academic_year')
                ->orderBy(['academic_year' => SORT_ASC])
                ->asArray()
                ->all();
            foreach ($gpaxData as $row) {
                $gpaxByYear[$row['academic_year']] = round($row['avg_gpax'], 2);
            }
        }

        // แผนอัตรากำลัง
        $latestPlan = AcademicRecruitmentPlan::find()->orderBy(['fiscal_year' => SORT_DESC])->one();
        $remainingQuota = $latestPlan ? $latestPlan->getRemainingQuota() : 0;

        // ผลสอบใบอนุญาต
        $licensePassed = LicenseExam::find()->where(['status' => 'passed'])->count();
        $licenseTotal = LicenseExam::find()->count();
        $licenseRate = $licenseTotal > 0 ? round($licensePassed / $licenseTotal * 100, 1) : 0;

        // === สถิติแยกตามข้อมูลหลัก ===

        // บุคลากรแยกตามสาขา
        $personnelByDept = Personnel::find()
            ->select(['d.name as label', 'COUNT(*) as total'])
            ->leftJoin('departments d', 'personnels.department_id = d.id')
            ->groupBy('personnels.department_id')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // บุคลากรแยกตามประเภทสัญญา
        $personnelByContract = Personnel::find()
            ->select(['c.name as label', 'COUNT(*) as total'])
            ->leftJoin('contract_types c', 'personnels.contract_type_id = c.id')
            ->groupBy('personnels.contract_type_id')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // บุคลากรแยกตามคุณวุฒิ
        $personnelByQualification = Personnel::find()
            ->select(['q.name as label', 'COUNT(*) as total'])
            ->leftJoin('qualifications q', 'personnels.qualification_id = q.id')
            ->groupBy('personnels.qualification_id')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // นักเรียนทุนแยกตามคุณวุฒิ
        $scholarByQualification = Scholarship::find()
            ->select(['q.name as label', 'COUNT(*) as total'])
            ->leftJoin('qualifications q', 'scholarships.qualification_id = q.id')
            ->groupBy('scholarships.qualification_id')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // ใบรับรองแยกตามระดับ
        $certByLevel = Certification::find()
            ->select(['cl.name as label', 'COUNT(*) as total'])
            ->leftJoin('certification_levels cl', 'certifications.certification_level_id = cl.id')
            ->groupBy('certifications.certification_level_id')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // ความเชี่ยวชาญยอดนิยม
        $topExpertises = PersonnelExpertise::find()
            ->select(['e.name as label', 'COUNT(*) as total'])
            ->leftJoin('expertises e', 'personnel_expertises.expertise_id = e.id')
            ->groupBy('personnel_expertises.expertise_id')
            ->orderBy(['total' => SORT_DESC])
            ->limit(10)
            ->asArray()->all();

        // === งานวิจัย และ บริการวิชาการ ===

        // งานวิจัย
        $totalResearch = Research::find()->count();
        $researchByStatus = Research::find()
            ->select(['work_status as label', 'COUNT(*) as total'])
            ->groupBy('work_status')
            ->asArray()->all();
        $researchByFunding = Research::find()
            ->select(['funding_source as label', 'COUNT(*) as total'])
            ->groupBy('funding_source')
            ->orderBy(['total' => SORT_DESC])
            ->limit(5)
            ->asArray()->all();

        // บริการวิชาการ
        $totalAcademicService = AcademicService::find()->count();
        $academicServiceByStatus = AcademicService::find()
            ->select(['status as label', 'COUNT(*) as total'])
            ->groupBy('status')
            ->asArray()->all();
        $totalParticipants = AcademicService::find()->sum('participants_count') ?? 0;

        // ข้อมูลแผนที่
        $researchLocations = Research::find()
            ->select(['id', 'title', 'latitude', 'longitude'])
            ->where(['not', ['latitude' => null]])
            ->andWhere(['not', ['longitude' => null]])
            ->asArray()->all();

        $academicServiceLocations = AcademicService::find()
            ->select(['id', 'activity_name', 'latitude', 'longitude'])
            ->where(['not', ['latitude' => null]])
            ->andWhere(['not', ['longitude' => null]])
            ->asArray()->all();

        return $this->render('index', [
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'graduatedStudents' => $graduatedStudents,
            'droppedStudents' => $droppedStudents,
            'inactiveStudents' => $inactiveStudents,
            'retentionRate' => $retentionRate,
            'totalPersonnel' => $totalPersonnel,
            'activePersonnel' => $activePersonnel,
            'totalScholarships' => $totalScholarships,
            'avgGpax' => round($avgGpax, 2),
            'latestYear' => $latestYear,
            'gpaxByYear' => $gpaxByYear,
            'latestPlan' => $latestPlan,
            'remainingQuota' => $remainingQuota,
            'licensePassed' => $licensePassed,
            'licenseTotal' => $licenseTotal,
            'licenseRate' => $licenseRate,
            // master data stats
            'personnelByDept' => $personnelByDept,
            'personnelByContract' => $personnelByContract,
            'personnelByQualification' => $personnelByQualification,
            'scholarByQualification' => $scholarByQualification,
            'certByLevel' => $certByLevel,
            'topExpertises' => $topExpertises,
            // research & academic service
            'totalResearch' => $totalResearch,
            'researchByStatus' => $researchByStatus,
            'researchByFunding' => $researchByFunding,
            'totalAcademicService' => $totalAcademicService,
            'academicServiceByStatus' => $academicServiceByStatus,
            'totalParticipants' => $totalParticipants,
            // map data
            'researchLocations' => $researchLocations,
            'academicServiceLocations' => $academicServiceLocations,
        ]);
    }
}

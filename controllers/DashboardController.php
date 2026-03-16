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
use app\models\BudgetAllocation;
use app\models\BudgetTransaction;
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
        // แผนอัตรากำลัง - ทั้งหมด
        $allPlans = AcademicRecruitmentPlan::find()->orderBy(['fiscal_year' => SORT_DESC])->all();

        // GPAX เฉลี่ยปัจจุบัน (ปีล่าสุด)
        $latestYear = StudentGrade::find()->max('academic_year');
        $avgGpax = 0;
        $maxGpax = 0;
        $minGpax = 0;
        $gpaxByYear = [];
        $gpaxByBatch = []; // GPAX per batch for LATEST year (for the new bar chart)
        $gpaxTrendByBatch = []; // GPAX trend per batch over years (for the line chart)
        $batchGpaxStats = []; // NEW: min/max/avg per batch

        if ($latestYear) {
            $gpaxQuery = StudentGrade::find()->where(['academic_year' => $latestYear]);
            $avgGpax = $gpaxQuery->average('gpax') ?? 0;
            $maxGpax = $gpaxQuery->max('gpax') ?? 0;
            $minGpax = $gpaxQuery->min('gpax') ?? 0;

            // GPAX per year for chart (Total)
            $gpaxData = StudentGrade::find()
                ->select(['academic_year', 'AVG(gpax) as avg_gpax'])
                ->groupBy('academic_year')
                ->orderBy(['academic_year' => SORT_ASC])
                ->asArray()
                ->all();
            foreach ($gpaxData as $row) {
                $gpaxByYear[$row['academic_year']] = round($row['avg_gpax'], 2);
            }

            // GPAX per batch (Cumulative average across all years)
            $batchData = StudentGrade::find()
                ->select([
                    's.batch', 
                    'AVG(student_grades.gpax) as avg_gpax',
                    'MAX(student_grades.gpax) as max_gpax', 
                    'MIN(student_grades.gpax) as min_gpax'
                ])
                ->leftJoin('students s', 'student_grades.student_id = s.student_id')
                ->andWhere(['not', ['s.batch' => null]])
                ->andWhere(['!=', 's.batch', ''])
                ->groupBy('s.batch')
                ->orderBy(['s.batch' => SORT_ASC])
                ->asArray()
                ->all();
            foreach ($batchData as $row) {
                $gpaxByBatch[$row['batch']] = round($row['avg_gpax'], 2);
                $batchGpaxStats[$row['batch']] = [
                    'avg' => round($row['avg_gpax'], 2),
                    'max' => round($row['max_gpax'], 2),
                    'min' => round($row['min_gpax'], 2)
                ];
            }

            // GPAX trend per batch over years
            $trendData = StudentGrade::find()
                ->select(['student_grades.academic_year', 's.batch', 'AVG(student_grades.gpax) as avg_gpax'])
                ->leftJoin('students s', 'student_grades.student_id = s.student_id')
                ->andWhere(['not', ['s.batch' => null]])
                ->andWhere(['!=', 's.batch', ''])
                ->groupBy(['student_grades.academic_year', 's.batch'])
                ->orderBy(['student_grades.academic_year' => SORT_ASC, 's.batch' => SORT_ASC])
                ->asArray()
                ->all();
            foreach ($trendData as $row) {
                $gpaxTrendByBatch[$row['batch']][$row['academic_year']] = round($row['avg_gpax'], 2);
            }

            // Cumulative GPAX Grouping (Total & By Batch)
            $avgGpaxByStudent = StudentGrade::find()
                ->select(['student_grades.student_id', 's.student_id as s_id', 's.fullname', 's.batch', 'AVG(student_grades.gpax) as avg_gpax'])
                ->leftJoin('students s', 'student_grades.student_id = s.student_id')
                ->groupBy(['student_grades.student_id', 's.student_id', 's.fullname', 's.batch'])
                ->asArray()
                ->all();

            $gpaxGroups = ['r1' => 0, 'r2' => 0, 'r3' => 0, 'r4' => 0];
            $gpaxGroupsByBatch = [];
            $gpaxStudentsList = ['total' => ['r1' => [], 'r2' => [], 'r3' => [], 'r4' => []]]; // Hold actual students

            foreach ($avgGpaxByStudent as $row) {
                if (empty($row['s_id']))
                    continue; // Skip if no matching student record

                $val = (float) $row['avg_gpax'];
                $batch = $row['batch'];
                $studentData = [
                    'id' => $row['s_id'],
                    'name' => $row['fullname'] ?? 'ไม่ระบุชื่อ'
                ];

                $range = null;
                if ($val >= 3.5)
                    $range = 'r1';
                elseif ($val >= 3.0)
                    $range = 'r2';
                elseif ($val >= 2.5)
                    $range = 'r3';
                elseif ($val >= 2.0)
                    $range = 'r4';

                if ($range) {
                    $gpaxGroups[$range]++;
                    $gpaxStudentsList['total'][$range][] = $studentData; // Add to total list

                    if ($batch) {
                        if (!isset($gpaxGroupsByBatch[$batch])) {
                            $gpaxGroupsByBatch[$batch] = ['r1' => 0, 'r2' => 0, 'r3' => 0, 'r4' => 0];
                            $gpaxStudentsList[$batch] = ['r1' => [], 'r2' => [], 'r3' => [], 'r4' => []];
                        }
                        $gpaxGroupsByBatch[$batch][$range]++;
                        $gpaxStudentsList[$batch][$range][] = $studentData; // Add to batch list
                    }
                }
            }

            // Retention Data per batch
            $retentionByBatch = [];
            foreach (array_keys($gpaxTrendByBatch) as $batch) {
                $retentionByBatch[$batch] = [
                    'active' => Student::find()->where(['batch' => $batch, 'status' => 'active'])->count(),
                    'graduated' => Student::find()->where(['batch' => $batch, 'status' => 'graduated'])->count(),
                    'inactive' => Student::find()->where(['batch' => $batch, 'status' => 'inactive'])->count(),
                    'dropped' => Student::find()->where(['batch' => $batch, 'status' => 'dropped'])->count(),
                ];
            }
        }

        // แผนอัตรากำลัง
        $latestPlan = AcademicRecruitmentPlan::find()->orderBy(['fiscal_year' => SORT_DESC])->one();
        $remainingQuota = $latestPlan ? $latestPlan->getRemainingQuota() : 0;

        // บุคลากร
        $activePersonnel = Personnel::find()->where(['status' => 1])->count();
        
        // อัตรากำลังย้อนหลัง 5 ปี
        $currentYear = (int)date('Y');
        $personnelRetentionStats = [];
        $allPersonnelData = Personnel::find()->where(['not', ['start_date' => null]])->all();

        for ($i = 4; $i >= 0; $i--) {
            $y = $currentYear - $i;
            $y_th = $y + 543;
            $activeCount = 0;
            
            foreach ($allPersonnelData as $p) {
                $startYear = (int)date('Y', strtotime($p->start_date));
                if ($startYear <= $y) {
                    $isLeft = false;
                    if ($p->status == 0) {
                        $leftYear = null;
                        if (!empty($p->resignation_year)) {
                            $leftYear = (int)$p->resignation_year - 543;
                        } elseif (!empty($p->contract_end_date)) {
                            $leftYear = (int)date('Y', strtotime($p->contract_end_date));
                        }
                        
                        if ($leftYear !== null && $leftYear <= $y) {
                            $isLeft = true;
                        } elseif ($leftYear === null) {
                            $isLeft = true;
                        }
                    }
                    if (!$isLeft) {
                        $activeCount++;
                    }
                }
            }
            $personnelRetentionStats[$y_th] = $activeCount; 
        }

        // นักเรียนทุน
        $totalScholarships = Scholarship::find()->count();

        // ผลสอบใบอนุญาต (ใช้ข้อมูลจาก ExamResult แทน LicenseExam เพื่อให้ตรงกับที่บันทึกจริง)
        $licenseTotal = ExamResult::find()->select('student_id')->distinct()->count();
        $licensePassed = ExamResult::find()->where(['status' => 'passed'])->select('student_id')->distinct()->count();
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

        // บุคลากรแยกตามสาย (ป/ว)
        $personnelByTrack = Personnel::find()
            ->select(['track as label', 'COUNT(*) as total'])
            ->where(['not', ['track' => null]])
            ->groupBy('track')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // บุคลากรแยกตามตำแหน่งทางวิชาการ
        $personnelByAcademicPosition = Personnel::find()
            ->select(['academic_position as label', 'COUNT(*) as total'])
            ->where(['not', ['academic_position' => null]])
            ->andWhere(['!=', 'academic_position', ''])
            ->groupBy('academic_position')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // บุคลากรแยกตามตำแหน่งงาน
        $personnelByJobPosition = Personnel::find()
            ->select(['job_position as label', 'COUNT(*) as total'])
            ->where(['not', ['job_position' => null]])
            ->andWhere(['!=', 'job_position', ''])
            ->groupBy('job_position')
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

        // นักเรียนทุนแยกตามสาขา
        $scholarByMajor = Scholarship::find()
            ->select(['major as label', 'COUNT(*) as total'])
            ->groupBy('major')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        // นักเรียนทุนแยกตามสถาบัน
        $scholarByInstitution = Scholarship::find()
            ->select(['institution as label', 'COUNT(*) as total'])
            ->groupBy('institution')
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

        // === งานวิจัย และ บริการวิชาการ/ทำนุบำรุงวัฒนธรรม ===

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

        $totalResearchBudget = Research::find()->sum('budget') ?? 0;

        $researchStats = Research::find()
            ->select(['year as label', 'COUNT(*) as total_projects', 'SUM(budget) as total_budget'])
            ->where(['not', ['year' => null]])
            ->andWhere(['!=', 'year', ''])
            ->groupBy('year')
            ->orderBy(['year' => SORT_ASC])
            ->asArray()->all();

        // บริการวิชาการ/ทำนุบำรุงวัฒนธรรม
        $totalAcademicService = AcademicService::find()->count();
        $academicServiceByStatus = AcademicService::find()
            ->select(['status as label', 'COUNT(*) as total'])
            ->groupBy('status')
            ->asArray()->all();
        $totalParticipants = AcademicService::find()->sum('participants_count') ?? 0;
        $totalAcademicBudget = AcademicService::find()->sum('budget_amount') ?? 0;

        // บริการวิชาการ/ทำนุบำรุงวัฒนธรรมรายปี
        $academicServiceStats = AcademicService::find()
            ->select(['fiscal_year as label', 'COUNT(*) as total_projects', 'SUM(budget_amount) as total_budget'])
            ->groupBy('fiscal_year')
            ->orderBy(['fiscal_year' => SORT_ASC])
            ->asArray()->all();

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

        // นวัตกรรม
        $totalInnovation = \app\models\Innovation::find()->count();

        // Extract year from invention_date 
        // We know the date format is YYYY-MM-DD
        $innovationStats = \app\models\Innovation::find()
            ->select([
                new \yii\db\Expression('YEAR(invention_date) as label'),
                'COUNT(*) as total_projects'
            ])
            ->where(['not', ['invention_date' => null]])
            ->groupBy(new \yii\db\Expression('YEAR(invention_date)'))
            ->orderBy(['label' => SORT_ASC])
            ->asArray()->all();

        // นวัตกรรม (Top 5 Advisors)
        $topInnovationAdvisors = \app\models\Innovation::find()
            ->select(['advisor as label', 'COUNT(*) as total'])
            ->where(['not', ['advisor' => null]])
            ->andWhere(['!=', 'advisor', ''])
            ->groupBy('advisor')
            ->orderBy(['total' => SORT_DESC])
            ->limit(5)
            ->asArray()->all();

        // งบประมาณ
        $budgetStatsRaw = BudgetAllocation::find()
            ->select([
                'fiscal_year as label',
                'SUM(allocated_amount) as total_allocated',
                'SUM(adjustment_reduction) as total_reduced',
                'SUM(adjustment_addition) as total_added',
            ])
            ->groupBy('fiscal_year')
            ->orderBy(['fiscal_year' => SORT_ASC])
            ->asArray()->all();

        $budgetStats = [];
        foreach ($budgetStatsRaw as $row) {
            $year = $row['label'];
            
            // Sum all expenses for this fiscal year
            $expenses = BudgetAllocation::find()
                ->where(['fiscal_year' => $year])
                ->all();
            
            $yearlySpent = 0;
            foreach ($expenses as $exp) {
                $yearlySpent += $exp->getTotalExpenses();
            }

            $budgetStats[] = [
                'label' => $year,
                'total_allocated' => (float)$row['total_allocated'],
                'total_reduced' => (float)$row['total_reduced'],
                'total_added' => (float)$row['total_added'],
                'total_budget' => (float)$row['total_allocated'] - (float)$row['total_reduced'] + (float)$row['total_added'],
                'total_spent' => (float)$yearlySpent,
            ];
        }

        $totalBudgetAllocated = BudgetAllocation::find()->sum('allocated_amount - adjustment_reduction + adjustment_addition') ?? 0;
        $totalBudgetSpent = BudgetTransaction::find()->sum('cost_compensation + cost_accommodation + cost_materials + cost_hospitality + cost_transportation') ?? 0;

        // Current Year Detailed Breakdown
        $latestBudgetYear = BudgetAllocation::find()->max('fiscal_year');
        $currentYearBreakdown = [];
        $currentYearTotalBudget = 0;
        $currentYearTotalSpent = 0;

        if ($latestBudgetYear) {
            $allocations = BudgetAllocation::find()
                ->where(['fiscal_year' => $latestBudgetYear])
                ->with('category')
                ->all();
            
            foreach ($allocations as $alloc) {
                $spent = $alloc->getTotalExpenses();
                $total = $alloc->getTotalBudget();
                $currentYearTotalBudget += $total;
                $currentYearTotalSpent += $spent;
                $currentYearBreakdown[] = [
                    'category' => $alloc->category->name ?? 'ไม่ระบุหมวด',
                    'total' => (float)$total,
                    'spent' => (float)$spent,
                    'percent' => $total > 0 ? round(($spent / $total) * 100, 2) : 0,
                ];
            }
            // Sort by percentage descending
            usort($currentYearBreakdown, fn($a, $b) => $b['percent'] <=> $a['percent']);
        }

        return $this->render('index', [
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'graduatedStudents' => $graduatedStudents,
            'droppedStudents' => $droppedStudents,
            'inactiveStudents' => $inactiveStudents,
            'retentionRate' => $retentionRate,
            'totalPersonnel' => $totalPersonnel,
            'activePersonnel' => $activePersonnel,
            'personnelRetentionStats' => $personnelRetentionStats,
            'totalScholarships' => $totalScholarships,
            'avgGpax' => round($avgGpax, 2),
            'maxGpax' => round($maxGpax, 2),
            'minGpax' => round($minGpax, 2),
            'latestYear' => $latestYear,
            'gpaxByYear' => $gpaxByYear,
            'gpaxByBatch' => $gpaxByBatch,
            'gpaxTrendByBatch' => $gpaxTrendByBatch,
            'batchGpaxStats' => $batchGpaxStats,
            'gpaxGroups' => $gpaxGroups ?? ['high' => 0, 'medium' => 0, 'low' => 0],
            'gpaxGroupsByBatch' => $gpaxGroupsByBatch ?? [],
            'gpaxStudentsList' => $gpaxStudentsList, // NEW: Full list of students by group
            'retentionByBatch' => $retentionByBatch ?? [],
            'allPlans' => $allPlans,
            'licensePassed' => $licensePassed,
            'licenseTotal' => $licenseTotal,
            'licenseRate' => $licenseRate,
            // master data stats
            'personnelByDept' => $personnelByDept,
            'personnelByContract' => $personnelByContract,
            'personnelByQualification' => $personnelByQualification,
            'personnelByTrack' => $personnelByTrack,
            'personnelByAcademicPosition' => $personnelByAcademicPosition,
            'personnelByJobPosition' => $personnelByJobPosition,
            'scholarByQualification' => $scholarByQualification,
            'scholarByMajor' => $scholarByMajor,
            'scholarByInstitution' => $scholarByInstitution,
            'certByLevel' => $certByLevel,
            'topExpertises' => $topExpertises,
            // research & academic service
            'totalResearch' => $totalResearch,
            'researchByStatus' => $researchByStatus,
            'researchByFunding' => $researchByFunding,
            'totalResearchBudget' => $totalResearchBudget,
            'researchStats' => $researchStats,
            'totalAcademicService' => $totalAcademicService,
            'academicServiceByStatus' => $academicServiceByStatus,
            'totalParticipants' => $totalParticipants,
            'totalAcademicBudget' => $totalAcademicBudget,
            'academicServiceStats' => $academicServiceStats,
            // map data
            'researchLocations' => $researchLocations,
            'academicServiceLocations' => $academicServiceLocations,
            'totalInnovation' => $totalInnovation,
            'innovationStats' => $innovationStats,
            'topInnovationAdvisors' => $topInnovationAdvisors,
            'budgetStats' => $budgetStats,
            'totalBudgetAllocated' => (float)$totalBudgetAllocated,
            'totalBudgetSpent' => (float)$totalBudgetSpent,
            'latestBudgetYear' => $latestBudgetYear,
            'currentYearTotalBudget' => (float)$currentYearTotalBudget,
            'currentYearTotalSpent' => (float)$currentYearTotalSpent,
            'currentYearBreakdown' => $currentYearBreakdown,
        ]);
    }
}

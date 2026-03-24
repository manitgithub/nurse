<?php

namespace app\controllers;

use app\models\Student;
use app\models\StudentGrade;
use app\models\StudentSearch;
use app\models\LicenseExam;
use app\models\ExamResult;
use app\models\ExamRound;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'view', 'export'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['create', 'update', 'delete'], 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Stats calculation
        $stats = [
            'total' => Student::find()->count(),
            'active' => Student::find()->where(['status' => 'active'])->count(),
            'graduated' => Student::find()->where(['status' => 'graduated'])->count(),
            'inactive' => Student::find()->where(['status' => 'inactive'])->count(),
            'dropped' => Student::find()->where(['status' => 'dropped'])->count(),
        ];

        // === Exam Statistics ===
        $examYears = Student::getGraduationYearList();
        $examSelectedYear = Yii::$app->request->get('exam_year', !empty($examYears) ? array_key_first($examYears) : null);

        $examStudentIds = Student::find()
            ->select('student_id')
            ->where(['graduation_year' => $examSelectedYear])
            ->column();

        $examTotalStudents = count($examStudentIds);

        $examRounds = ExamRound::find()
            ->innerJoinWith('examResults')
            ->where(['exam_results.student_id' => $examStudentIds])
            ->groupBy('exam_rounds.id')
            ->orderBy(['exam_rounds.year' => SORT_ASC, 'exam_rounds.round_number' => SORT_ASC])
            ->all();

        $subjectLabels = ExamResult::getSubjectLabels();
        $examRoundStats = [];
        $examSubjectStats = [];

        foreach ($examRounds as $round) {
            $results = ExamResult::find()
                ->where(['round_id' => $round->id, 'student_id' => $examStudentIds])
                ->all();

            $examCount = count($results);
            $roundPassed = 0;
            $roundFailed = 0;
            $subjects = [];
            for ($i = 1; $i <= 8; $i++) {
                $subjects["subject_{$i}"] = ['pass' => 0, 'fail' => 0, 'na' => 0];
            }

            foreach ($results as $r) {
                if ($r->status === 'passed') $roundPassed++;
                else $roundFailed++;

                for ($i = 1; $i <= 8; $i++) {
                    $attr = "subject_{$i}_score";
                    $key = "subject_{$i}";
                    if ($r->$attr === 'P') $subjects[$key]['pass']++;
                    elseif ($r->$attr === 'F') $subjects[$key]['fail']++;
                    else $subjects[$key]['na']++;
                }
            }

            $examRoundStats[] = [
                'round' => $round,
                'exam_count' => $examCount,
                'passed' => $roundPassed,
                'failed' => $roundFailed,
                'pass_rate' => $examCount > 0 ? round(($roundPassed / $examCount) * 100, 1) : 0,
                'subjects' => $subjects,
            ];
        }

        for ($i = 1; $i <= 8; $i++) {
            $key = "subject_{$i}";
            $totalPass = $totalFail = $totalTaken = 0;
            foreach ($examRoundStats as $rs) {
                $totalPass += $rs['subjects'][$key]['pass'];
                $totalFail += $rs['subjects'][$key]['fail'];
                $totalTaken += $rs['subjects'][$key]['pass'] + $rs['subjects'][$key]['fail'];
            }
            $examSubjectStats[$key] = [
                'label' => $subjectLabels[$key],
                'pass' => $totalPass,
                'fail' => $totalFail,
                'total' => $totalTaken,
                'pass_rate' => $totalTaken > 0 ? round(($totalPass / $totalTaken) * 100, 1) : 0,
            ];
        }

        $examOverallPassed = ExamResult::find()
            ->select('student_id')
            ->where(['student_id' => $examStudentIds, 'status' => 'passed'])
            ->distinct()
            ->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stats' => $stats,
            // Exam stats
            'examYears' => $examYears,
            'examSelectedYear' => $examSelectedYear,
            'examTotalStudents' => $examTotalStudents,
            'examOverallPassed' => (int) $examOverallPassed,
            'examOverallPassRate' => $examTotalStudents > 0 ? round(($examOverallPassed / $examTotalStudents) * 100, 1) : 0,
            'examRoundStats' => $examRoundStats,
            'examSubjectStats' => $examSubjectStats,
            'subjectLabels' => $subjectLabels,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $gradesProvider = new ActiveDataProvider([
            'query' => StudentGrade::find()->where(['student_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'academic_year' => SORT_DESC, // Fallback
                ],
                'attributes' => [
                    'academic_year' => [
                        'asc' => [
                            new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', -1) ASC"),
                            new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', 1) ASC"),
                        ],
                        'desc' => [
                            new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', -1) DESC"),
                            new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', 1) ASC"),
                        ],
                    ],
                ],
            ],
        ]);
        // Since we want Year DESC, Semester ASC as default:
        $gradesProvider->query->orderBy([
            new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', -1) DESC"),
            new \yii\db\Expression("SUBSTRING_INDEX(academic_year, '/', 1) ASC"),
        ]);
        $licenseProvider = new ActiveDataProvider([
            'query' => LicenseExam::find()->where(['student_id' => $id]),
        ]);

        $examResultsProvider = new ActiveDataProvider([
            'query' => \app\models\ExamResult::find()->where(['student_id' => $id])->with(['round']),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        return $this->render('view', [
            'model' => $model,
            'gradesProvider' => $gradesProvider,
            'licenseProvider' => $licenseProvider,
            'examResultsProvider' => $examResultsProvider,
            'trend' => $model->getGpaxTrend(),
            'history' => $model->getGpaxHistoryChronological(),
        ]);
    }

    public function actionCreate()
    {
        $model = new Student();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลนักศึกษาสำเร็จ');
            return $this->redirect(['view', 'id' => $model->student_id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลสำเร็จ');
            return $this->redirect(['view', 'id' => $model->student_id]);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'ลบข้อมูลสำเร็จ');
        return $this->redirect(['index']);
    }

    public function actionExport()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false; // Get all filtered data
        $models = $dataProvider->getModels();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['รหัสนักศึกษา', 'รุ่น', 'ชื่อ-นามสกุล', 'โรงเรียนมัธยม', 'GPAX มัธยม', 'อาจารย์ที่ปรึกษา', 'สถานะ'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        foreach ($headers as $index => $header) {
            $sheet->setCellValue($cols[$index] . '1', $header);
        }

        // Style header
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Data
        $row = 2;
        $statusLabels = Student::getStatusList();
        foreach ($models as $model) {
            $sheet->setCellValue('A' . $row, $model->student_id);
            $sheet->setCellValue('B' . $row, $model->batch);
            $sheet->setCellValue('C' . $row, $model->fullname);
            $sheet->setCellValue('D' . $row, $model->high_school);
            $sheet->setCellValue('E' . $row, $model->gpax_hs);
            $sheet->setCellValue('F' . $row, $model->advisor ? $model->advisor->fullname : '-');
            $sheet->setCellValue('G' . $row, $statusLabels[$model->status] ?? $model->status);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $colId) {
            $sheet->getColumnDimension($colId)->setAutoSize(true);
        }

        $filename = 'students_export_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

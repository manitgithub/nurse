<?php

namespace app\controllers;

use app\models\ExamResult;
use app\models\Student;
use app\models\ExamRound;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ExamResultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'view', 'statistics'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['create', 'update', 'delete', 'batch-create', 'get-students'], 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = ExamResult::find()->with(['student', 'round']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        // Calculate statistics
        $stats = [
            'total' => ExamResult::find()->select('student_id')->distinct()->count(),
            'passed' => ExamResult::find()->where(['status' => 'passed'])->select('student_id')->distinct()->count(),
            'pending' => ExamResult::find()->where(['status' => 'pending'])->select('student_id')->distinct()->count(),
            'failed' => ExamResult::find()->where(['status' => 'failed'])->select('student_id')->distinct()->count(),
        ];
        $stats['pass_rate'] = $stats['total'] > 0 ? round(($stats['passed'] / $stats['total']) * 100, 1) : 0;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'stats' => $stats,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new ExamResult();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'เพิ่มผลสอบสำเร็จ');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'student_id', 'fullname'),
            'rounds' => ArrayHelper::map(ExamRound::find()->all(), 'id', function ($model) {
                return "ปี {$model->year} รอบที่ {$model->round_number}";
            }),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลสำเร็จ');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'student_id', 'fullname'),
            'rounds' => ArrayHelper::map(ExamRound::find()->all(), 'id', function ($model) {
                return "ปี {$model->year} รอบที่ {$model->round_number}";
            }),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'ลบข้อมูลสำเร็จ');
        return $this->redirect(['index']);
    }

    /**
     * AJAX: Get students by graduation year (Excluding those who already passed)
     */
    public function actionGetStudents($graduation_year)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Find students who have already passed the exam
        $passedStudentIds = ExamResult::find()
            ->where(['status' => 'passed'])
            ->select('student_id')
            ->column();

        $students = Student::find()
            ->where(['graduation_year' => $graduation_year])
            ->andWhere(['not in', 'student_id', $passedStudentIds])
            ->orderBy(['student_id' => SORT_ASC])
            ->asArray()
            ->all();
        return $students;
    }

    /**
     * Batch create exam results for all students in a graduation year
     */
    public function actionBatchCreate()
    {
        $years = Student::getGraduationYearList();
        $rounds = ArrayHelper::map(ExamRound::find()->orderBy(['year' => SORT_DESC, 'round_number' => SORT_DESC])->all(), 'id', function ($model) {
            return "ปี {$model->year} รอบที่ {$model->round_number}";
        });

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $roundId = $post['round_id'] ?? null;
            $results = $post['ExamResult'] ?? [];
            $saved = 0;
            $errors = [];

            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($results as $studentId => $data) {
                    // Skip if all subjects are empty
                    $hasData = false;
                    for ($i = 1; $i <= 8; $i++) {
                        if (!empty($data["subject_{$i}_score"])) {
                            $hasData = true;
                            break;
                        }
                    }
                    if (!$hasData)
                        continue;

                    // Check if result already exists
                    $studentIdStr = (string) $studentId;
                    $model = ExamResult::findOne(['student_id' => $studentIdStr, 'round_id' => $roundId]);
                    if (!$model) {
                        $model = new ExamResult();
                        $model->student_id = $studentIdStr;
                        $model->round_id = $roundId;
                    }

                    for ($i = 1; $i <= 8; $i++) {
                        $attr = "subject_{$i}_score";
                        $model->$attr = !empty($data[$attr]) ? $data[$attr] : null;
                    }
                    $model->status = $data['status'] ?? null;

                    if ($model->save()) {
                        $saved++;
                    } else {
                        $errors[] = $studentIdStr . ': ' . implode(', ', $model->getFirstErrors());
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', "บันทึกผลสอบสำเร็จ {$saved} รายการ");
                if (!empty($errors)) {
                    Yii::$app->session->setFlash('error', 'ข้อผิดพลาด: ' . implode('; ', $errors));
                }
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        }

        return $this->render('batch-create', [
            'years' => $years,
            'rounds' => $rounds,
        ]);
    }

    /**
     * Exam statistics page – filterable by graduation year
     */
    public function actionStatistics($graduation_year = null)
    {
        $years = Student::getGraduationYearList();

        // Default to latest year
        if ($graduation_year === null && !empty($years)) {
            $graduation_year = array_key_first($years);
        }

        // Get student IDs for the selected graduation year
        $studentIds = Student::find()
            ->select('student_id')
            ->where(['graduation_year' => $graduation_year])
            ->column();

        $totalStudents = count($studentIds);

        // Get all exam rounds that have results for these students
        $rounds = ExamRound::find()
            ->innerJoinWith('examResults')
            ->where(['exam_results.student_id' => $studentIds])
            ->groupBy('exam_rounds.id')
            ->orderBy(['exam_rounds.year' => SORT_ASC, 'exam_rounds.round_number' => SORT_ASC])
            ->all();

        $subjectLabels = ExamResult::getSubjectLabels();
        $roundStats = [];
        $subjectStats = [];

        foreach ($rounds as $round) {
            $results = ExamResult::find()
                ->where(['round_id' => $round->id, 'student_id' => $studentIds])
                ->all();

            $examCount = count($results);
            $roundPassed = 0;
            $roundFailed = 0;

            // Init subject counters for this round
            $subjects = [];
            for ($i = 1; $i <= 8; $i++) {
                $key = "subject_{$i}";
                $subjects[$key] = ['pass' => 0, 'fail' => 0, 'na' => 0];
            }

            foreach ($results as $r) {
                if ($r->status === 'passed') {
                    $roundPassed++;
                } else {
                    $roundFailed++;
                }

                for ($i = 1; $i <= 8; $i++) {
                    $attr = "subject_{$i}_score";
                    $key = "subject_{$i}";
                    if ($r->$attr === 'P') {
                        $subjects[$key]['pass']++;
                    } elseif ($r->$attr === 'F') {
                        $subjects[$key]['fail']++;
                    } else {
                        $subjects[$key]['na']++;
                    }
                }
            }

            $roundStats[] = [
                'round' => $round,
                'exam_count' => $examCount,
                'passed' => $roundPassed,
                'failed' => $roundFailed,
                'pass_rate' => $examCount > 0 ? round(($roundPassed / $examCount) * 100, 1) : 0,
                'subjects' => $subjects,
            ];
        }

        // Aggregate subject stats across all rounds
        for ($i = 1; $i <= 8; $i++) {
            $key = "subject_{$i}";
            $totalPass = 0;
            $totalFail = 0;
            $totalTaken = 0;
            foreach ($roundStats as $rs) {
                $totalPass += $rs['subjects'][$key]['pass'];
                $totalFail += $rs['subjects'][$key]['fail'];
                $totalTaken += $rs['subjects'][$key]['pass'] + $rs['subjects'][$key]['fail'];
            }
            $subjectStats[$key] = [
                'label' => $subjectLabels[$key],
                'pass' => $totalPass,
                'fail' => $totalFail,
                'total' => $totalTaken,
                'pass_rate' => $totalTaken > 0 ? round(($totalPass / $totalTaken) * 100, 1) : 0,
            ];
        }

        // Overall unique passed students
        $overallPassed = ExamResult::find()
            ->select('student_id')
            ->where(['student_id' => $studentIds, 'status' => 'passed'])
            ->distinct()
            ->count();

        return $this->render('statistics', [
            'years' => $years,
            'selectedYear' => $graduation_year,
            'totalStudents' => $totalStudents,
            'overallPassed' => (int) $overallPassed,
            'overallPassRate' => $totalStudents > 0 ? round(($overallPassed / $totalStudents) * 100, 1) : 0,
            'roundStats' => $roundStats,
            'subjectStats' => $subjectStats,
            'subjectLabels' => $subjectLabels,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = ExamResult::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

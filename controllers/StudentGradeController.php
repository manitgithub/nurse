<?php

namespace app\controllers;

use app\models\Student;
use app\models\StudentGrade;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentGradeController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => StudentGrade::find()->with('student'),
            'sort' => ['defaultOrder' => ['academic_year' => SORT_DESC, 'student_id' => SORT_ASC]],
            'pagination' => ['pageSize' => 20],
        ]);

        // Calculate statistics by batch
        $stats = StudentGrade::find()
            ->select([
                'students.batch',
                'AVG(student_grades.gpax) as avg_gpax',
                'MAX(student_grades.gpax) as max_gpax',
                'MIN(student_grades.gpax) as min_gpax',
                'COUNT(*) as count'
            ])
            ->innerJoin('students', 'student_grades.student_id = students.student_id')
            ->groupBy('students.batch')
            ->orderBy(['students.batch' => SORT_ASC])
            ->asArray()
            ->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'stats' => $stats,
        ]);
    }

    public function actionCreate()
    {
        $model = new StudentGrade();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'บันทึกผลการเรียนสำเร็จ');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'student_id', 'fullname'),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'แก้ไขผลการเรียนสำเร็จ');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'student_id', 'fullname'),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'ลบข้อมูลสำเร็จ');
        return $this->redirect(['index']);
    }

    /**
     * Import GPAX from Excel
     */
    public function actionImport()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('excelFile');
            if ($file) {
                try {
                    $spreadsheet = IOFactory::load($file->tempName);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();

                    $successCount = 0;
                    $errorCount = 0;
                    $errors = [];

                    // Skip header (row 0)
                    for ($i = 1; $i < count($data); $i++) {
                        $row = $data[$i];
                        if (empty($row[0]) || empty($row[1]) || empty($row[2]))
                            continue;

                        $term = trim($row[0]);
                        $year = trim($row[1]);
                        $studentId = trim($row[2]);
                        $gpax = trim($row[3]);

                        $academicYear = $term . '/' . $year;

                        $model = StudentGrade::findOne([
                            'student_id' => $studentId,
                            'academic_year' => $academicYear
                        ]) ?: new StudentGrade();

                        $model->student_id = $studentId;
                        $model->academic_year = $academicYear;
                        $model->gpax = $gpax;

                        if ($model->save()) {
                            $successCount++;
                        } else {
                            $errorCount++;
                            $errors[] = "Row " . ($i + 1) . " ($studentId): " . implode(', ', $model->getFirstErrors());
                        }
                    }

                    Yii::$app->session->setFlash('success', "นำเข้าข้อมูลสำเร็จ {$successCount} รายการ");
                    if ($errorCount > 0) {
                        Yii::$app->session->setFlash('error', "พบข้อผิดพลาด {$errorCount} รายการ: " . implode('; ', $errors));
                    }
                    return $this->redirect(['index']);

                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาดในการอ่านไฟล์: ' . $e->getMessage());
                }
            }
        }

        return $this->render('import');
    }

    /**
     * Batch Create GPAX
     */
    public function actionBatchCreate()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Grades', []);
            $academicYear = Yii::$app->request->post('academic_year');

            $successCount = 0;
            foreach ($data as $studentId => $values) {
                if (isset($values['gpax']) && $values['gpax'] !== '') {
                    $studentIdStr = (string)$studentId;
                    $model = StudentGrade::findOne(['student_id' => $studentIdStr, 'academic_year' => $academicYear]) ?: new StudentGrade();
                    $model->student_id = $studentIdStr;
                    $model->academic_year = $academicYear;
                    $model->gpax = $values['gpax'];
                    if ($model->save()) {
                        $successCount++;
                    }
                }
            }

            Yii::$app->session->setFlash('success', "บันทึกผลการเรียนสำเร็จ {$successCount} รายการ");
            return $this->redirect(['index']);
        }

        return $this->render('batch-create');
    }

    /**
     * AJAX: Get students for batch grading
     */
    public function actionGetStudents($batch, $year)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $students = Student::find()
            ->where(['batch' => $batch])
            ->orderBy(['student_id' => SORT_ASC])
            ->asArray()
            ->all();

        // Join with existing grades if any
        foreach ($students as &$student) {
            $grade = StudentGrade::findOne(['student_id' => $student['student_id'], 'academic_year' => $year]);
            $student['gpax'] = $grade ? $grade->gpax : '';
        }

        return $students;
    }

    protected function findModel($id)
    {
        if (($model = StudentGrade::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

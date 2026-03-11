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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
                    $model = StudentGrade::findOne(['student_id' => $studentId, 'academic_year' => $academicYear]) ?: new StudentGrade();
                    $model->student_id = $studentId;
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

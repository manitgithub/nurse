<?php

namespace app\controllers;

use app\models\Student;
use app\models\StudentGrade;
use app\models\LicenseExam;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class StudentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'view'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['create', 'update', 'delete'], 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Student::find(),
            'sort' => ['defaultOrder' => ['student_id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $gradesProvider = new ActiveDataProvider([
            'query' => StudentGrade::find()->where(['student_id' => $id]),
            'sort' => ['defaultOrder' => ['academic_year' => SORT_DESC]],
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

    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

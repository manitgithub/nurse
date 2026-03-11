<?php

namespace app\controllers;

use app\models\Personnel;
use app\models\PersonnelExpertise;
use app\models\Expertise;
use app\models\Qualification;
use app\models\ContractType;
use app\models\Department;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PersonnelController extends Controller
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
            'query' => Personnel::find()->with(['qualification', 'contractType', 'department']),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Personnel();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveExpertises($model);
            Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลบุคลากรสำเร็จ');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', array_merge(
            ['model' => $model],
            $this->getFormData($model)
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveExpertises($model);
            Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลสำเร็จ');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', array_merge(
            ['model' => $model],
            $this->getFormData($model)
        ));
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // Delete related expertise links
        PersonnelExpertise::deleteAll(['personnel_id' => $model->id]);
        $model->delete();
        Yii::$app->session->setFlash('success', 'ลบข้อมูลสำเร็จ');
        return $this->redirect(['index']);
    }

    /**
     * Get common form data (dropdowns)
     */
    protected function getFormData($model)
    {
        $selectedExpertises = ArrayHelper::getColumn(
            PersonnelExpertise::find()->where(['personnel_id' => $model->id])->all(),
            'expertise_id'
        );
        return [
            'qualifications' => ArrayHelper::map(Qualification::find()->where(['status' => 1])->all(), 'id', 'name'),
            'contractTypes' => ArrayHelper::map(ContractType::find()->where(['status' => 1])->all(), 'id', 'name'),
            'departments' => ArrayHelper::map(Department::find()->where(['status' => 1])->all(), 'id', 'name'),
            'expertiseList' => ArrayHelper::map(Expertise::find()->orderBy('name')->all(), 'id', 'name'),
            'selectedExpertises' => $selectedExpertises,
        ];
    }

    /**
     * Save expertise links from checkbox form
     */
    protected function saveExpertises($model)
    {
        $expertiseIds = Yii::$app->request->post('PersonnelExpertise', []);
        // Delete old links
        PersonnelExpertise::deleteAll(['personnel_id' => $model->id]);
        // Insert new links
        foreach ($expertiseIds as $eid) {
            $pe = new PersonnelExpertise();
            $pe->personnel_id = $model->id;
            $pe->expertise_id = (int) $eid;
            $pe->save();
        }
    }

    protected function findModel($id)
    {
        if (($model = Personnel::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

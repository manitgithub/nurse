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
use yii\web\UploadedFile;

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
            $this->handleFileUploads($model);
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
            $this->handleFileUploads($model);
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
     * Handle file uploads (photo, license, member card)
     */
    protected function handleFileUploads($model)
    {
        $uploadDir = Yii::getAlias('@webroot') . '/uploads/personnel/' . $model->id;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Handle Photo (if any)
        if ($photo = UploadedFile::getInstance($model, 'photo')) {
            $fileName = 'photo_' . time() . '.' . $photo->extension;
            if ($photo->saveAs($uploadDir . '/' . $fileName)) {
                $model->photo = 'uploads/personnel/' . $model->id . '/' . $fileName;
            }
        }

        // Handle License File
        if ($license_file = UploadedFile::getInstance($model, 'license_file')) {
            $fileName = 'license_' . time() . '.' . $license_file->extension;
            if ($license_file->saveAs($uploadDir . '/' . $fileName)) {
                $model->license_file = 'uploads/personnel/' . $model->id . '/' . $fileName;
            }
        }

        // Handle Member Card File
        if ($member_card_file = UploadedFile::getInstance($model, 'member_card_file')) {
            $fileName = 'member_' . time() . '.' . $member_card_file->extension;
            if ($member_card_file->saveAs($uploadDir . '/' . $fileName)) {
                $model->member_card_file = 'uploads/personnel/' . $model->id . '/' . $fileName;
            }
        }

        $model->save(false, ['photo', 'license_file', 'member_card_file']);
    }

    protected function findModel($id)
    {
        if (($model = Personnel::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

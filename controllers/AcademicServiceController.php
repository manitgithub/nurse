<?php

namespace app\controllers;

use app\models\AcademicService;
use app\models\AcademicServiceFile;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class AcademicServiceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'view'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['create', 'update', 'delete', 'delete-file'], 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AcademicService::find(),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new AcademicService();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->handleFileUploads($model);
            Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลบริการวิชาการสำเร็จ');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->handleFileUploads($model);
            Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลสำเร็จ');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // Delete associated files from disk
        foreach ($model->files as $file) {
            $filePath = Yii::getAlias('@webroot') . '/' . $file->file_path;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $model->delete();
        Yii::$app->session->setFlash('success', 'ลบข้อมูลสำเร็จ');
        return $this->redirect(['index']);
    }

    public function actionDeleteFile($id, $fileId)
    {
        $file = AcademicServiceFile::findOne($fileId);
        if ($file && $file->academic_service_id == $id) {
            $filePath = Yii::getAlias('@webroot') . '/' . $file->file_path;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $file->delete();
            Yii::$app->session->setFlash('success', 'ลบไฟล์สำเร็จ');
        }
        return $this->redirect(['update', 'id' => $id]);
    }

    protected function handleFileUploads($model)
    {
        $uploadDir = Yii::getAlias('@webroot') . '/uploads/academic-services/' . $model->id;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Handle attachment files
        $attachments = UploadedFile::getInstancesByName('attachments');
        foreach ($attachments as $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->extension;
            $filePath = $uploadDir . '/' . $fileName;
            if ($file->saveAs($filePath)) {
                $fileModel = new AcademicServiceFile();
                $fileModel->academic_service_id = $model->id;
                $fileModel->file_path = 'uploads/academic-services/' . $model->id . '/' . $fileName;
                $fileModel->file_type = 'attachment';
                $fileModel->original_name = $file->baseName . '.' . $file->extension;
                $fileModel->save();
            }
        }

        // Handle image files
        $images = UploadedFile::getInstancesByName('images');
        foreach ($images as $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->extension;
            $filePath = $uploadDir . '/' . $fileName;
            if ($file->saveAs($filePath)) {
                $fileModel = new AcademicServiceFile();
                $fileModel->academic_service_id = $model->id;
                $fileModel->file_path = 'uploads/academic-services/' . $model->id . '/' . $fileName;
                $fileModel->file_type = 'image';
                $fileModel->original_name = $file->baseName . '.' . $file->extension;
                $fileModel->save();
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = AcademicService::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

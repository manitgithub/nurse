<?php

namespace app\controllers;

use app\models\Innovation;
use app\models\InnovationFile;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class InnovationController extends Controller
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
        $searchModel = new \app\models\InnovationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Get total stats
        $totalInnovation = Innovation::find()->count();

        // Group by year using invention_date
        $yearCounts = Innovation::find()
            ->select([
                new \yii\db\Expression('YEAR(invention_date) as year'),
                'COUNT(*) as count'
            ])
            ->where(['not', ['invention_date' => null]])
            ->groupBy(new \yii\db\Expression('YEAR(invention_date)'))
            ->orderBy(['year' => SORT_DESC])
            ->asArray()
            ->all();

        // Convert to associative array for easier access
        $yearSummary = [];
        foreach ($yearCounts as $row) {
            $year = empty($row['year']) ? 'ไม่ระบุ' : $row['year'];
            $yearSummary[$year] = $row['count'];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalInnovation' => $totalInnovation,
            'yearSummary' => $yearSummary,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Innovation();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->handleFileUploads($model);
            Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลนวัตกรรมสำเร็จ');
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
        $file = InnovationFile::findOne($fileId);
        if ($file && $file->innovation_id == $id) {
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
        $uploadDir = Yii::getAlias('@webroot') . '/uploads/innovations/' . $model->id;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $attachments = UploadedFile::getInstancesByName('attachments');
        foreach ($attachments as $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->extension;
            $filePath = $uploadDir . '/' . $fileName;
            if ($file->saveAs($filePath)) {
                $fileModel = new InnovationFile();
                $fileModel->innovation_id = $model->id;
                $fileModel->file_path = 'uploads/innovations/' . $model->id . '/' . $fileName;
                $fileModel->file_type = 'attachment';
                $fileModel->original_name = $file->baseName . '.' . $file->extension;
                $fileModel->save();
            }
        }

        $images = UploadedFile::getInstancesByName('images');
        foreach ($images as $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->extension;
            $filePath = $uploadDir . '/' . $fileName;
            if ($file->saveAs($filePath)) {
                $fileModel = new InnovationFile();
                $fileModel->innovation_id = $model->id;
                $fileModel->file_path = 'uploads/innovations/' . $model->id . '/' . $fileName;
                $fileModel->file_type = 'image';
                $fileModel->original_name = $file->baseName . '.' . $file->extension;
                $fileModel->save();
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = Innovation::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

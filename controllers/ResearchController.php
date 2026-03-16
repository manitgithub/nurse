<?php

namespace app\controllers;

use app\models\Research;
use app\models\ResearchFile;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ResearchController extends Controller
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
        $searchModel = new \app\models\ResearchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Get total stats
        $totalProjects = Research::find()->count();
        $totalBudget = Research::find()->sum('budget') ?? 0;

        // Group by status
        $statusCounts = Research::find()
            ->select(['work_status', 'COUNT(*) as count'])
            ->groupBy('work_status')
            ->asArray()
            ->all();

        // Convert to associative array for easier access
        $statusSummary = [];
        foreach ($statusCounts as $row) {
            $status = empty($row['work_status']) ? 'ไม่ระบุ' : $row['work_status'];
            $statusSummary[$status] = $row['count'];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalProjects' => $totalProjects,
            'totalBudget' => $totalBudget,
            'statusSummary' => $statusSummary,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Research();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->handleFileUploads($model);
            Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลงานวิจัยสำเร็จ');
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
        $file = ResearchFile::findOne($fileId);
        if ($file && $file->research_id == $id) {
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
        $uploadDir = Yii::getAlias('@webroot') . '/uploads/researches/' . $model->id;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $attachments = UploadedFile::getInstancesByName('attachments');
        foreach ($attachments as $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->extension;
            $filePath = $uploadDir . '/' . $fileName;
            if ($file->saveAs($filePath)) {
                $fileModel = new ResearchFile();
                $fileModel->research_id = $model->id;
                $fileModel->file_path = 'uploads/researches/' . $model->id . '/' . $fileName;
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
                $fileModel = new ResearchFile();
                $fileModel->research_id = $model->id;
                $fileModel->file_path = 'uploads/researches/' . $model->id . '/' . $fileName;
                $fileModel->file_type = 'image';
                $fileModel->original_name = $file->baseName . '.' . $file->extension;
                $fileModel->save();
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = Research::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

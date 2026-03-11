<?php

namespace app\controllers;

use app\models\Personnel;
use app\models\Certification;
use app\models\CertificationLevel;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class CertificationController extends Controller
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

    /**
     * UKPSF Batch Creation
     */
    public function actionBatchCreate()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Certifications', []);
            $successCount = 0;

            foreach ($data as $personnelId => $values) {
                if (!empty($values['certification_level_id'])) {
                    // Find existing or create new
                    $model = Certification::findOne(['personnel_id' => $personnelId]) ?: new Certification();
                    $model->personnel_id = $personnelId;
                    $model->certification_level_id = $values['certification_level_id'] ?: null;
                    $model->certified_date = $values['certified_date'] ?: null;
                    $model->training_batch = $values['training_batch'] ?: null;

                    if ($model->save()) {
                        $successCount++;
                    }
                }
            }

            Yii::$app->session->setFlash('success', "บันทึกข้อมูล UKPSF สำเร็จ {$successCount} รายการ");
            return $this->redirect(['batch-create']);
        }

        $levels = ArrayHelper::map(CertificationLevel::find()->where(['not', ['id' => null]])->all(), 'id', 'name');
        return $this->render('batch-create', [
            'levels' => $levels
        ]);
    }

    /**
     * AJAX: Get personnel for batch certification
     */
    public function actionAjaxGetPersonnel()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $personnel = Personnel::find()
            ->with(['department'])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($personnel as $p) {
            $cert = Certification::findOne(['personnel_id' => $p->id]);
            $result[] = [
                'id' => $p->id,
                'fullname' => $p->fullname,
                'department_name' => $p->department->name ?? 'N/A',
                'certification_level_id' => $cert ? $cert->certification_level_id : '',
                'certified_date' => $cert ? $cert->certified_date : '',
                'training_batch' => $cert ? $cert->training_batch : '',
            ];
        }

        return $result;
    }
}

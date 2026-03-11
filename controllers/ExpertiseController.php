<?php

namespace app\controllers;

use app\models\Expertise;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ExpertiseController extends Controller
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
            'query' => Expertise::find(),
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new Expertise();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลสำเร็จ');
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลสำเร็จ');
            return $this->redirect(['index']);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'ลบข้อมูลสำเร็จ');
        return $this->redirect(['index']);
    }

    /**
     * Batch Update Personnel Expertise
     */
    public function actionBatchUpdate()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('PersonnelExpertise', []);
            $successCount = 0;

            foreach ($data as $personnelId => $expertiseIds) {
                // Remove all current expertise for this person
                \app\models\PersonnelExpertise::deleteAll(['personnel_id' => $personnelId]);

                // Add new ones
                if (is_array($expertiseIds)) {
                    foreach ($expertiseIds as $eid) {
                        if (!empty($eid)) {
                            $pe = new \app\models\PersonnelExpertise();
                            $pe->personnel_id = $personnelId;
                            $pe->expertise_id = (int) $eid;
                            $pe->save();
                        }
                    }
                    $successCount++;
                }
            }

            Yii::$app->session->setFlash('success', "ปรับปรุงความเชี่ยวชาญสำเร็จบุคลากร {$successCount} ท่าน");
            return $this->redirect(['batch-update']);
        }

        return $this->render('batch-update');
    }

    /**
     * AJAX: Get personnel and their expertises
     */
    public function actionAjaxGetPersonnel()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $personnel = \app\models\Personnel::find()->with(['department'])->orderBy(['id' => SORT_ASC])->all();
        $expertiseList = ArrayHelper::map(Expertise::find()->orderBy('name')->all(), 'id', 'name');

        $result = [];
        foreach ($personnel as $p) {
            $selected = \app\models\PersonnelExpertise::find()
                ->where(['personnel_id' => $p->id])
                ->select(['expertise_id'])
                ->column();

            $result[] = [
                'id' => $p->id,
                'fullname' => $p->fullname,
                'department_name' => $p->department->name ?? 'N/A',
                'selected_expertises' => array_map('strval', $selected),
            ];
        }

        return [
            'personnel' => $result,
            'expertiseList' => $expertiseList
        ];
    }

    protected function findModel($id)
    {
        if (($model = Expertise::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ไม่พบข้อมูล');
    }
}

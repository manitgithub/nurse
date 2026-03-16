<?php

namespace app\controllers;

use Yii;
use app\models\BudgetCategory;
use app\models\BudgetAllocation;
use app\models\BudgetTransaction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class BudgetController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($fiscal_year = null)
    {
        if ($fiscal_year === null) {
            $fiscal_year = (date('Y') + 543); // Current Thai Year
        }

        $categories = BudgetCategory::find()->where(['status' => 1])->all();
        $allocations = BudgetAllocation::find()
            ->where(['fiscal_year' => $fiscal_year])
            ->indexBy('category_id')
            ->all();

        return $this->render('index', [
            'categories' => $categories,
            'allocations' => $allocations,
            'fiscal_year' => $fiscal_year,
        ]);
    }

    public function actionAllocation($id = null, $category_id = null)
    {
        if ($id) {
            $model = BudgetAllocation::findOne($id);
        } else {
            $model = new BudgetAllocation();
            $model->category_id = $category_id;
            $model->fiscal_year = (date('Y') + 543);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'fiscal_year' => $model->fiscal_year]);
        }

        return $this->render('allocation', [
            'model' => $model,
        ]);
    }

    public function actionTransactions($allocation_id)
    {
        $allocation = BudgetAllocation::findOne($allocation_id);
        if (!$allocation) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $transactions = BudgetTransaction::find()
            ->where(['allocation_id' => $allocation_id])
            ->orderBy(['transaction_date' => SORT_ASC])
            ->all();

        return $this->render('transactions', [
            'allocation' => $allocation,
            'transactions' => $transactions,
        ]);
    }

    public function actionCreateTransaction($allocation_id)
    {
        $model = new BudgetTransaction();
        $model->allocation_id = $allocation_id;
        $model->transaction_date = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['transactions', 'allocation_id' => $allocation_id]);
        }

        return $this->render('create-transaction', [
            'model' => $model,
        ]);
    }
    public function actionUpdateTransaction($id)
    {
        $model = BudgetTransaction::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['transactions', 'allocation_id' => $model->allocation_id]);
        }

        return $this->render('create-transaction', [
            'model' => $model,
        ]);
    }

    public function actionDeleteTransaction($id)
    {
        $model = BudgetTransaction::findOne($id);
        if ($model) {
            $allocation_id = $model->allocation_id;
            $model->delete();
            return $this->redirect(['transactions', 'allocation_id' => $allocation_id]);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

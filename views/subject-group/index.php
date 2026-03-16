<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubjectGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'จัดการสาขาตามโครงสร้าง';
?>
<div class="subject-group-index">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">📚 <?= Html::encode($this->title) ?></h1>
        <?= Html::a('+ เพิ่มสาขาตามโครงสร้าง', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
            'headerRowOptions' => ['class' => 'bg-gray-50'],
            'rowOptions' => ['class' => 'hover:bg-gray-50 transition'],
            'layout' => "{items}\n<div class='p-4 border-t border-gray-200 flex justify-end'>{pager}</div>",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'contentOptions' => ['class' => 'px-6 py-4 text-sm text-gray-500']],

                [
                    'attribute' => 'name',
                    'contentOptions' => ['class' => 'px-6 py-4 text-sm font-medium text-gray-900'],
                    'headerOptions' => ['class' => 'px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider'],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->status
                                    ? '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">ใช้งาน</span>'
                                    : '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">ยกเลิก</span>';
                            },
                    'filter' => [1 => 'ใช้งาน', 0 => 'ยกเลิก'],
                    'contentOptions' => ['class' => 'px-6 py-4 text-sm text-gray-500'],
                    'headerOptions' => ['class' => 'px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider'],
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['class' => 'px-6 py-4 text-right text-sm font-medium space-x-2'],
                    'headerOptions' => ['class' => 'px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider'],
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                                    return Html::a('ดู', $url, ['class' => 'text-indigo-600 hover:text-indigo-900']);
                                },
                        'update' => function ($url, $model, $key) {
                                    return Html::a('แก้ไข', $url, ['class' => 'text-amber-600 hover:text-amber-900']);
                                },
                        'delete' => function ($url, $model, $key) {
                                    return Html::a('ลบ', $url, [
                                        'class' => 'text-red-600 hover:text-red-900',
                                        'data' => [
                                            'confirm' => 'ยืนยันการลบข้อมูล?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                },
                    ],
                ],
            ],
            'pager' => [
                'options' => ['class' => 'flex space-x-1 justify-center'],
                'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50'],
                'disabledListItemSubTagOptions' => ['tag' => 'span', 'class' => 'px-3 py-1 rounded bg-gray-50 border border-gray-200 text-sm text-gray-400'],
                'activePageCssClass' => 'bg-indigo-50 border-indigo-500 text-indigo-600 font-bold',
            ],
        ]); ?>
    </div>

</div>
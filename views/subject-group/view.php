<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubjectGroup */

$this->title = $model->name;
?>
<div class="subject-group-view">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">📚 <?= Html::encode($this->title) ?></h1>
        <div class="space-x-2">
            <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
            <?= Html::a('🗑 ลบ', ['delete', 'id' => $model->id], [
                'class' => 'px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium shadow-sm',
                'data' => [
                    'confirm' => 'ยืนยันการลบข้อมูล?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'min-w-full divide-y divide-gray-200'],
            'template' => '<tr class="hover:bg-gray-50 transition"><th class="px-6 py-4 text-left text-sm font-bold text-gray-500 uppercase tracking-wider w-1/3 bg-gray-50/50">{label}</th><td class="px-6 py-4 text-sm text-gray-900">{value}</td></tr>',
            'attributes' => [
                'id',
                'name',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->status
                            ? '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">ใช้งาน</span>'
                            : '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">ยกเลิก</span>';
                    },
                ],
            ],
        ]) ?>
    </div>

</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'นวัตกรรม';
?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">💡
        <?= Html::encode($this->title) ?>
    </h1>
    <?= Html::a('+ เพิ่มนวัตกรรม', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-5 rounded-lg shadow-sm transition-all']) ?>
</div>

<!-- ==================== SUMMARY CARDS ==================== -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div
        class="bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl shadow-sm p-6 text-white flex flex-col justify-center text-center">
        <h3 class="text-lg font-medium opacity-90">จำนวนนวัตกรรมทั้งหมด</h3>
        <p class="text-5xl font-bold mt-2"><?= number_format($totalInnovation) ?></p>
        <p class="text-purple-100 mt-2 text-sm">ผลงาน</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-3 border-b pb-2">ผลงานแยกตามปี</h3>
        <div class="space-y-2 max-h-32 overflow-y-auto pr-2">
            <?php foreach ($yearSummary as $year => $count): ?>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">ปี <?= Html::encode($year) ?></span>
                    <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-full text-xs"><?= $count ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ==================== FILTER SECTION ==================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'flex flex-col sm:flex-row gap-4 items-end']
    ]); ?>

    <div class="flex-1 w-full relative">
        <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">ค้นหานวัตกรรม (ชื่อ,
            ผู้สร้างสรรค์, อาจารย์ที่ปรึกษา, ปี)</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                🔍
            </span>
            <?= $form->field($searchModel, 'globalSearch')->textInput([
                'class' => 'w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition',
                'placeholder' => 'พิมพ์คำค้นหา...',
            ])->label(false) ?>
        </div>
    </div>

    <div>
        <?= Html::submitButton('กรองข้อมูล', ['class' => 'w-full sm:w-auto px-6 py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-lg hover:bg-indigo-100 border border-indigo-200 transition shadow-sm']) ?>
        <?= Html::a('ล้าง', ['index'], ['class' => 'w-full sm:w-auto px-4 py-2 bg-white text-gray-600 font-medium rounded-lg hover:bg-gray-50 border border-gray-300 transition shadow-sm ml-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        ชื่อนวัตกรรม</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ว/ด/ป
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        อาจารย์ที่ปรึกษา</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">จัดการ
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($dataProvider->getModels() as $model): ?>
                    <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                <?= Html::encode($model->name) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $model->invention_date ? (function($d){ try { return (new \DateTime($d))->format('d/m/Y'); } catch(\Exception $e) { return '-'; } })($model->invention_date) : '-' ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= Html::encode($model->advisor) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <?= Html::a('ดู', ['view', 'id' => $model->id], ['class' => 'text-indigo-600 hover:text-indigo-900']) ?>
                            <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-amber-600 hover:text-amber-900']) ?>
                            <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
                                'class' => 'text-red-600 hover:text-red-900',
                                'data' => [
                                    'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($dataProvider->getCount() === 0): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">ยังไม่มีข้อมูลนวัตกรรม</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'flex justify-center space-x-1'],
            'linkContainerOptions' => ['class' => 'inline-block'],
            'linkOptions' => ['class' => 'px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-100'],
            'disabledPageCssClass' => 'opacity-50 cursor-not-allowed',
            'activePageCssClass' => 'bg-indigo-600 text-white border-indigo-600',
        ]) ?>
    </div>
</div>
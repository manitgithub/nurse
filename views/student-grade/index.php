<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'ผลการเรียน (GPAX)';
?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">🎓
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="flex space-x-2">
        <?= Html::a('📤 นำเข้า Excel', ['import'], ['class' => 'bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
        <?= Html::a('📋 บันทึกแบบกลุ่ม', ['batch-create'], ['class' => 'bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
        <?= Html::a('+ เพิ่มผลการเรียน', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>
</div>

<?php if (!empty($stats)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php foreach ($stats as $stat): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                        รหัส <?= Html::encode($stat['batch'] ?: 'N/A') ?>
                    </span>
                    <span class="text-xs text-gray-400"><?= $stat['count'] ?> รายการ</span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-end">
                        <span class="text-sm text-gray-500">ค่าเฉลี่ย (Avg)</span>
                        <span class="text-xl font-bold text-indigo-600"><?= number_format($stat['avg_gpax'], 2) ?></span>
                    </div>
                    <div class="flex justify-between text-xs pt-2 border-t border-gray-50">
                        <div class="flex flex-col">
                            <span class="text-gray-400">สูงสุด (Max)</span>
                            <span class="font-semibold text-emerald-600"><?= number_format($stat['max_gpax'], 2) ?></span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-400">ต่ำสุด (Min)</span>
                            <span class="font-semibold text-rose-600"><?= number_format($stat['min_gpax'], 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        นักศึกษา</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        ปีการศึกษา</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">GPAX
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">จัดการ
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($dataProvider->getModels() as $model): ?>
                    <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= Html::encode($model->student->fullname ?? 'N/A') ?>
                                </div>
                                <div class="ml-2 text-xs text-gray-400">
                                    (
                                    <?= Html::encode($model->student_id) ?>)
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <?= Html::encode($model->academic_year) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span
                                class="px-2.5 py-1 rounded-full text-sm font-bold <?= $model->gpax >= 3.5 ? 'bg-indigo-100 text-indigo-700' : ($model->gpax >= 2.0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') ?>">
                                <?= number_format($model->gpax, 2) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-indigo-600 hover:text-indigo-900']) ?>
                            <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
                                'class' => 'text-red-600 hover:text-red-900',
                                'data' => [
                                    'confirm' => 'ยืนยันการลบข้อมูลนี้?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($dataProvider->getCount() === 0): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">ยังไม่มีข้อมูลผลการเรียน</td>
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
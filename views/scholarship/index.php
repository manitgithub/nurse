<?php
use yii\helpers\Html;
$this->title = 'นักเรียนทุน';
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">🎓
        <?= Html::encode($this->title) ?>
    </h1>
    <?= Html::a('+ เพิ่มนักเรียนทุน', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>
<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ปีการศึกษา</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ชื่อนักเรียนทุน</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ระดับคุณวุฒิ</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถาบัน</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สาขา</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        <?= Html::encode($model->academic_year) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?= Html::encode($model->scholar_name) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->qualification->name ?? '-') ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->institution) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->major) ?>
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <?= Html::a('ดู', ['view', 'id' => $model->id], ['class' => 'text-indigo-600 hover:text-indigo-900 font-medium']) ?>
                        <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-amber-600 hover:text-amber-900 font-medium']) ?>
                        <?= Html::a('ลบ', ['delete', 'id' => $model->id], ['class' => 'text-red-600 hover:text-red-900 font-medium', 'data' => ['confirm' => 'ลบ?', 'method' => 'post']]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($dataProvider->getTotalCount() == 0): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="mt-4">
    <?= \yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination, 'options' => ['class' => 'flex space-x-1 justify-center'], 'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50']]) ?>
</div>
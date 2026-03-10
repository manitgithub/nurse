<?php
use yii\helpers\Html;
$this->title = 'แผนก';
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">🏢 <?= Html::encode($this->title) ?></h1>
    <?= Html::a('+ เพิ่ม', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>
<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ชื่อแผนก</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($dataProvider->getModels() as $model): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-sm text-gray-500"><?= $model->id ?></td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= \yii\helpers\Html::encode($model->name) ?></td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $model->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $model->status ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm space-x-2">
                    <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-amber-600 hover:text-amber-900 font-medium']) ?>
                    <?= Html::a('ลบ', ['delete', 'id' => $model->id], ['class' => 'text-red-600 hover:text-red-900 font-medium', 'data' => ['confirm' => 'ลบ?', 'method' => 'post']]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if ($dataProvider->getTotalCount() == 0): ?>
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

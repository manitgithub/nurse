<?php
use yii\helpers\Html;
?>
<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รหัส</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ตำแหน่งงาน</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สาขา / กลุ่มวิชา</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ประเภทสัญญา</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-indigo-600">
                        <?= Html::encode($model->personnel_code) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->job_position ?? '-') ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?= Html::encode($model->fullname) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->department->name ?? '-') ?>
                        <br>
                        <span class="text-xs text-indigo-500"><?= Html::encode($model->subjectGroup->name ?? '') ?></span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->contractType->name ?? '-') ?>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $model->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $model->status ? 'ปฏิบัติงาน' : 'ไม่ปฏิบัติงาน' ?>
                        </span>
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
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="mt-4">
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'options' => ['class' => 'flex space-x-1 justify-center'],
        'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50']
    ]) ?>
</div>

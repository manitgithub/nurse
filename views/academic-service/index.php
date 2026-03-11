<?php
use yii\helpers\Html;
$this->title = 'บริการวิชาการ';
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">📋
        <?= Html::encode($this->title) ?>
    </h1>
    <?= Html::a('+ เพิ่มกิจกรรม', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>
<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">กิจกรรม</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ปีงบประมาณ</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">งบประมาณ (บาท)</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">วันที่</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs truncate">
                        <?= Html::encode($model->activity_name) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->fiscal_year) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 text-right">
                        <?= $model->budget_amount ? number_format($model->budget_amount, 2) : '-' ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= $model->start_date ?>
                        <?= $model->end_date ? ' - ' . $model->end_date : '' ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <?php
                        $statusColor = 'bg-gray-100 text-gray-700';
                        if ($model->status === 'ดำเนินการเสร็จสิ้นแล้ว') {
                            $statusColor = 'bg-green-100 text-green-700';
                        } elseif ($model->status === 'กำลังดำเนินการ') {
                            $statusColor = 'bg-blue-100 text-blue-700';
                        }
                        ?>
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $statusColor ?>">
                            <?= Html::encode($model->status) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <?= Html::a('ดู', ['view', 'id' => $model->id], ['class' => 'text-indigo-600 hover:text-indigo-900 font-medium']) ?>
                        <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-amber-600 hover:text-amber-900 font-medium']) ?>
                        <?= Html::a('ลบ', ['delete', 'id' => $model->id], ['class' => 'text-red-600 hover:text-red-900 font-medium', 'data' => ['confirm' => 'ลบข้อมูลนี้?', 'method' => 'post']]) ?>
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
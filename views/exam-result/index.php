<?php
use yii\helpers\Html;
$this->title = 'ผลสอบ';
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">📝
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="flex space-x-2">
        <?= Html::a('📋 บันทึกแบบกลุ่ม', ['batch-create'], ['class' => 'bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
        <?= Html::a('+ เพิ่มผลสอบ', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>
</div>

<!-- Stats Summary -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">ผู้เข้าสอบทั้งหมด</div>
        <div class="text-2xl font-bold text-gray-900"><?= number_format($stats['total']) ?> <span
                class="text-sm font-normal text-gray-400">คน</span></div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-green-500">
        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">สอบผ่านแล้ว</div>
        <div class="text-2xl font-bold text-green-600"><?= number_format($stats['passed']) ?> <span
                class="text-sm font-normal text-gray-400">คน</span></div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-amber-500">
        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">รอผล/ไม่ผ่าน</div>
        <div class="text-2xl font-bold text-amber-600"><?= number_format($stats['pending'] + $stats['failed']) ?> <span
                class="text-sm font-normal text-gray-400">คน</span></div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-indigo-500">
        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">อัตราการสอบผ่าน</div>
        <div class="text-2xl font-bold text-indigo-600"><?= $stats['pass_rate'] ?> <span
                class="text-sm font-normal text-gray-400">%</span></div>
    </div>
</div>
<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">นักศึกษา</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รอบสอบ</th>
                    <?php
                    $subjectLabels = \app\models\ExamResult::getSubjectLabels();
                    for ($i = 1; $i <= 8; $i++): ?>
                        <th class="px-2 py-3 text-center text-xs font-semibold text-gray-500 uppercase"
                            title="<?= Html::encode($subjectLabels["subject_{$i}"]) ?>">
                            ว.<?= $i ?>
                        </th>
                    <?php endfor; ?>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($dataProvider->getModels() as $model): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">
                            <div class="font-medium text-indigo-600"><?= Html::encode($model->student->student_id ?? '') ?>
                            </div>
                            <div class="text-xs text-gray-500"><?= Html::encode($model->student->fullname ?? '-') ?></div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            <?= $model->round ? "ปี {$model->round->year} รอบ {$model->round->round_number}" : '-' ?>
                        </td>
                        <?php for ($i = 1; $i <= 8; $i++):
                            $attr = "subject_{$i}_score"; ?>
                            <td class="px-2 py-3 text-sm text-center font-semibold">
                                <?php if ($model->$attr === 'P'): ?>
                                    <span class="text-green-600">P</span>
                                <?php elseif ($model->$attr === 'F'): ?>
                                    <span class="text-red-500">F</span>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td class="px-4 py-3 text-sm">
                            <?php $statusLabels = \app\models\ExamResult::getStatusList(); ?>
                            <span
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?= $model->status === 'passed' ? 'bg-green-100 text-green-800' : ($model->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') ?>">
                                <?= $statusLabels[$model->status] ?? $model->status ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-2">
                            <?= Html::a('ดู', ['view', 'id' => $model->id], ['class' => 'text-indigo-600 hover:text-indigo-900 font-medium']) ?>
                            <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-amber-600 hover:text-amber-900 font-medium']) ?>
                            <?= Html::a('ลบ', ['delete', 'id' => $model->id], ['class' => 'text-red-600 hover:text-red-900 font-medium', 'data' => ['confirm' => 'ลบ?', 'method' => 'post']]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($dataProvider->getTotalCount() == 0): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    <?= \yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination, 'options' => ['class' => 'flex space-x-1 justify-center'], 'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50']]) ?>
</div>
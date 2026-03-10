<?php
use yii\helpers\Html;

/** @var app\models\Student $model */
/** @var yii\data\ActiveDataProvider $gradesProvider */
/** @var yii\data\ActiveDataProvider $licenseProvider */

$this->title = $model->fullname;
$statusLabels = \app\models\Student::getStatusList();
?>

<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">👤
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->student_id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('🗑 ลบ', ['delete', 'id' => $model->student_id], [
            'class' => 'px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium shadow-sm',
            'data' => ['confirm' => 'คุณต้องการลบรายการนี้?', 'method' => 'post'],
        ]) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Student Info -->
    <div class="lg:col-span-2 bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">ข้อมูลนักศึกษา</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">รหัสนักศึกษา</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->student_id) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ชื่อ-นามสกุล</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->fullname) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">โรงเรียนมัธยม</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->high_school) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">GPAX มัธยม</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= $model->gpax_hs ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ภูมิลำเนา</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->hometown) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
                <dd class="text-sm mt-1">
                    <?= $statusLabels[$model->status] ?? $model->status ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- Stats Card -->
    <div class="space-y-4">
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 shadow-sm rounded-xl p-6 text-white">
            <h3 class="text-sm font-medium opacity-80">GPA ล่าสุด</h3>
            <p class="text-3xl font-bold mt-1">
                <?= $model->latestGrade ? number_format($model->latestGrade->gpax, 2) : 'N/A' ?>
            </p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 shadow-sm rounded-xl p-6 text-white">
            <h3 class="text-sm font-medium opacity-80">ผลสอบใบอนุญาต</h3>
            <p class="text-3xl font-bold mt-1">
                <?= count($model->licenseExams) ?> ครั้ง
            </p>
        </div>
    </div>
</div>

<!-- Grades History -->
<div class="mt-6 bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">📊 ประวัติผลการเรียน</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ปีการศึกษา</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">GPAX</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($gradesProvider->getModels() as $grade): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm text-gray-900">
                        <?= Html::encode($grade->academic_year) ?>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-900 font-medium">
                        <?= number_format($grade->gpax, 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($gradesProvider->getTotalCount() == 0): ?>
                <tr>
                    <td colspan="2" class="px-6 py-4 text-center text-gray-400 text-sm">ยังไม่มีข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
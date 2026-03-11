<?php
use yii\helpers\Html;
$this->title = 'ผลสอบ #' . $model->id;
$statusLabels = \app\models\ExamResult::getStatusList();
?>
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>
<div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <dt class="text-sm font-medium text-gray-500">นักศึกษา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->student->fullname ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">รอบสอบ</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $model->round ? "ปี {$model->round->year} รอบ {$model->round->round_number}" : '-' ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
            <dd class="text-sm mt-1">
                <?= $statusLabels[$model->status] ?? $model->status ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สอบผ่าน</dt>
            <dd class="text-lg font-bold text-indigo-600 mt-1">
                <?= $model->getPassedCount() ?> / 8 วิชา
            </dd>
        </div>
    </div>
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">ผลการสอบรายวิชา</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $subjectLabels = \app\models\ExamResult::getSubjectLabels();
        for ($i = 1; $i <= 8; $i++):
            $attr = "subject_{$i}_score"; ?>
            <div
                class="bg-gray-50 rounded-lg p-3 text-center border <?= $model->$attr === 'P' ? 'border-green-200 bg-green-50' : ($model->$attr === 'F' ? 'border-red-200 bg-red-50' : '') ?>">
                <p class="text-xs text-gray-500" title="<?= Html::encode($subjectLabels["subject_{$i}"]) ?>">
                    <?= Html::encode($subjectLabels["subject_{$i}"]) ?>
                </p>
                <p
                    class="text-lg font-bold <?= $model->$attr === 'P' ? 'text-green-600' : ($model->$attr === 'F' ? 'text-red-500' : 'text-gray-400') ?>">
                    <?= $model->$attr === 'P' ? 'ผ่าน' : ($model->$attr === 'F' ? 'ไม่ผ่าน' : '-') ?>
                </p>
            </div>
        <?php endfor; ?>
    </div>
</div>
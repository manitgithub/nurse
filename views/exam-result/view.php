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
            <dt class="text-sm font-medium text-gray-500">คะแนนรวม</dt>
            <dd class="text-lg font-bold text-indigo-600 mt-1">
                <?= number_format($model->getTotalScore(), 2) ?>
            </dd>
        </div>
    </div>
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">คะแนนรายวิชา</h3>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <?php for ($i = 1; $i <= 10; $i++):
            $attr = "subject_{$i}_score"; ?>
            <div class="bg-gray-50 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-500">วิชา
                    <?= $i ?>
                </p>
                <p class="text-lg font-semibold text-gray-900">
                    <?= $model->$attr !== null ? number_format($model->$attr, 1) : '-' ?>
                </p>
            </div>
        <?php endfor; ?>
    </div>
</div>
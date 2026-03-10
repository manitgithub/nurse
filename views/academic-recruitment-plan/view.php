<?php
use yii\helpers\Html;
$this->title = 'แผนอัตรากำลัง ปี ' . $model->fiscal_year;
$remaining = $model->getRemainingQuota();
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
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-sm">
        <h3 class="text-sm font-medium opacity-80">อัตราที่ได้รับ</h3>
        <p class="text-3xl font-bold mt-1">
            <?= $model->quota_amount ?>
        </p>
    </div>
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-6 text-white shadow-sm">
        <h3 class="text-sm font-medium opacity-80">บรรจุแล้ว</h3>
        <p class="text-3xl font-bold mt-1">
            <?= $model->recruited_amount ?>
        </p>
    </div>
    <div
        class="bg-gradient-to-br <?= $remaining > 0 ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600' ?> rounded-xl p-6 text-white shadow-sm">
        <h3 class="text-sm font-medium opacity-80">อัตราคงเหลือ</h3>
        <p class="text-3xl font-bold mt-1">
            <?= $remaining ?>
        </p>
    </div>
</div>
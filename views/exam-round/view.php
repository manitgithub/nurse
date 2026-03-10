<?php
use yii\helpers\Html;
$this->title = 'รอบสอบ: ปี ' . $model->year . ' รอบ ' . $model->round_number;
?>
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition shadow-sm font-medium']) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>
<div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200 max-w-lg">
    <dl class="space-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">ปี พ.ศ.</dt>
            <dd class="text-lg text-gray-900">
                <?= $model->year ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">รอบที่</dt>
            <dd class="text-lg text-gray-900">
                <?= $model->round_number ?>
            </dd>
        </div>
    </dl>
</div>
<?php
use yii\helpers\Html;
$this->title = $model->scholar_name;
?>
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">🎓
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>
<div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200 max-w-2xl">
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">ปีการศึกษา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->academic_year) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ชื่อนักเรียนทุน</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->scholar_name) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ระดับคุณวุฒิ</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->qualification->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สถาบัน</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->institution) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สาขา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->major) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันเริ่มต้น</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $model->start_date ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันสิ้นสุด</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $model->end_date ?>
            </dd>
        </div>
    </dl>
</div>
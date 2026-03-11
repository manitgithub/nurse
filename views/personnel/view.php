<?php
use yii\helpers\Html;
$this->title = $model->fullname;
$genderLabels = \app\models\Personnel::getGenderList();
?>
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">👤
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('🗑 ลบ', ['delete', 'id' => $model->id], ['class' => 'px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium shadow-sm', 'data' => ['confirm' => 'ลบ?', 'method' => 'post']]) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>
<div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">รหัสบุคลากร</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->personnel_code) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ชื่อ-นามสกุล</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->fullname) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">เพศ</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $genderLabels[$model->gender] ?? '-' ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">อีเมล</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->email) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">โทรศัพท์</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->phone) ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันเกิด</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $model->birth_date ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันเริ่มงาน</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $model->start_date ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">วันสิ้นสุดสัญญา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= $model->contract_end_date ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">คุณวุฒิ</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->qualification->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">ประเภทสัญญา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->contractType->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สาขา</dt>
            <dd class="text-sm text-gray-900 mt-1">
                <?= Html::encode($model->department->name ?? '-') ?>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
            <dd class="text-sm mt-1">
                <span
                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $model->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <?= $model->status ? 'ปฏิบัติงาน' : 'ไม่ปฏิบัติงาน' ?>
                </span>
            </dd>
        </div>
    </dl>
</div>
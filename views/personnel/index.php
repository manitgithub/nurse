<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'จัดการบุคลากร';
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">👥
        <?= Html::encode($this->title) ?>
    </h1>
    <?= Html::a('+ เพิ่มบุคลากร', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">บุคลากรทั้งหมด</p>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total']) ?></p>
    </div>
    <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 shadow-sm">
        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider mb-1">สายวิชาการ</p>
        <p class="text-2xl font-bold text-indigo-700"><?= number_format($stats['academic']) ?></p>
    </div>
    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 shadow-sm">
        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mb-1">สายปฏิบัติการ</p>
        <p class="text-2xl font-bold text-amber-700"><?= number_format($stats['operational']) ?></p>
    </div>
    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 shadow-sm">
        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">ปฏิบัติงาน</p>
        <p class="text-2xl font-bold text-emerald-700"><?= number_format($stats['active']) ?></p>
    </div>
    <div class="bg-rose-50 p-4 rounded-xl border border-rose-100 shadow-sm">
        <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wider mb-1">ไม่ปฏิบัติงาน</p>
        <p class="text-2xl font-bold text-rose-700"><?= number_format($stats['inactive']) ?></p>
    </div>
</div>

<!-- Filter Form -->
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'grid grid-cols-1 md:grid-cols-6 gap-4 items-end']
    ]); ?>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">รหัสบุคลากร</label>
        <?= $form->field($searchModel, 'personnel_code')->textInput(['placeholder' => 'ค้นหา...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ชื่อ-นามสกุล</label>
        <?= $form->field($searchModel, 'fullname')->textInput(['placeholder' => 'ค้นหา...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สายงาน</label>
        <?= $form->field($searchModel, 'track')->dropDownList(\app\models\Personnel::getTrackList(), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ตำแหน่งงาน</label>
        <?= $form->field($searchModel, 'job_position')->dropDownList(\app\models\Personnel::getJobPositionList(), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สาขาวิชา</label>
        <?= $form->field($searchModel, 'department_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Department::find()->all(), 'id', 'name'), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สาขาตามโครงสร้าง</label>
        <?= $form->field($searchModel, 'subject_group_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\SubjectGroup::find()->all(), 'id', 'name'), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สถานะ</label>
        <?= $form->field($searchModel, 'status')->dropDownList([1 => 'ปฏิบัติงาน', 0 => 'ไม่ปฏิบัติงาน'], ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="flex space-x-2">
        <?= Html::submitButton('🔍', ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition flex-1']) ?>
        <?= Html::a('ล้าง', ['index'], ['class' => 'bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
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
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="mt-4">
    <?= \yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination, 'options' => ['class' => 'flex space-x-1 justify-center'], 'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50']]) ?>
</div>
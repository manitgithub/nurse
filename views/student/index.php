<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\StudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $stats */

$this->title = 'จัดการนักศึกษา';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">📚 <?= Html::encode($this->title) ?></h1>
        <p class="text-sm text-gray-500 mt-1">จัดการข้อมูลและติดตามสถานะนักศึกษา</p>
    </div>
    <div class="flex space-x-2">
        <?= Html::a('📊 ส่งออก Excel', ['export'] + Yii::$app->request->queryParams, ['class' => 'bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition flex items-center']) ?>
        <?= Html::a('+ เพิ่มนักศึกษา', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs font-bold text-gray-500 uppercase mb-1">นักศึกษาทั้งหมด</p>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total']) ?></p>
    </div>
    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 shadow-sm">
        <p class="text-xs font-bold text-emerald-600 uppercase mb-1 font-bold">กำลังศึกษา</p>
        <p class="text-2xl font-bold text-emerald-700"><?= number_format($stats['active']) ?></p>
    </div>
    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 shadow-sm">
        <p class="text-xs font-bold text-blue-600 uppercase mb-1 font-bold">สำเร็จการศึกษา</p>
        <p class="text-2xl font-bold text-blue-700"><?= number_format($stats['graduated']) ?></p>
    </div>
    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 shadow-sm">
        <p class="text-xs font-bold text-amber-600 uppercase mb-1 font-bold">พักการเรียน</p>
        <p class="text-2xl font-bold text-amber-700"><?= number_format($stats['inactive']) ?></p>
    </div>
    <div class="bg-rose-50 p-4 rounded-xl border border-rose-100 shadow-sm">
        <p class="text-xs font-bold text-rose-600 uppercase mb-1 font-bold">พ้นสภาพ</p>
        <p class="text-2xl font-bold text-rose-700"><?= number_format($stats['dropped']) ?></p>
    </div>
</div>

<!-- Filter Form -->
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
    <?php $form = \yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'grid grid-cols-1 md:grid-cols-4 gap-4 items-end']
    ]); ?>

    <?= $form->field($searchModel, 'student_id', ['options' => ['class' => 'm-0']])->textInput(['placeholder' => 'ค้นหารหัสนักศึกษา...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('รหัสนักศึกษา', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>

    <?= $form->field($searchModel, 'fullname', ['options' => ['class' => 'm-0']])->textInput(['placeholder' => 'ค้นหาชื่อ-นามสกุล...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('ชื่อ-นามสกุล', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>

    <div class="grid grid-cols-2 gap-2">
        <?= $form->field($searchModel, 'batch', ['options' => ['class' => 'm-0']])->dropDownList(\app\models\Student::getBatchList(), ['prompt' => 'ทุกรุ่น', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('รุ่น', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>

        <?= $form->field($searchModel, 'status', ['options' => ['class' => 'm-0']])->dropDownList(\app\models\Student::getStatusList(), ['prompt' => 'ทุกสถานะ', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('สถานะ', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>
    </div>

    <div>
        <?php
        $advisors = \app\models\Personnel::find()->where(['track' => 'สาย ว'])->orderBy(['fullname' => SORT_ASC])->all();
        $advisorList = \yii\helpers\ArrayHelper::map($advisors, 'id', 'fullname');
        echo $form->field($searchModel, 'advisor_id', ['options' => ['class' => 'm-0']])->dropDownList($advisorList, ['prompt' => 'อาจารย์ที่ปรึกษาทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('อาจารย์ที่ปรึกษา', ['class' => 'block text-sm font-bold text-gray-700 mb-1']);
        ?>
    </div>

    <div class="flex space-x-2">
        <?= Html::submitButton('🔍 ค้นหา', ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition flex-1']) ?>
        <?= Html::a('ล้าง', ['index'], ['class' => 'bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>

    <?php \yii\widgets\ActiveForm::end(); ?>
</div>

<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รหัสนักศึกษา</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รุ่น</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">โรงเรียนมัธยม</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">GPAX มัธยม</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">อาจารย์ที่ปรึกษา</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                        <?= Html::encode($model->student_id) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($model->batch): ?>
                            <span
                                class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">รหัส
                                <?= Html::encode($model->batch) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?= Html::encode($model->fullname) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= Html::encode($model->high_school) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= $model->gpax_hs ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= $model->advisor ? Html::encode($model->advisor->fullname) : '-' ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                        $statusColors = ['active' => 'green', 'inactive' => 'yellow', 'graduated' => 'blue', 'dropped' => 'red'];
                        $statusLabels = \app\models\Student::getStatusList();
                        $color = $statusColors[$model->status] ?? 'gray';
                        ?>
                        <span
                            class="inline-flex items-center rounded-full bg-<?= $color ?>-100 px-2.5 py-0.5 text-xs font-medium text-<?= $color ?>-800">
                            <?= $statusLabels[$model->status] ?? $model->status ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                        <?= Html::a('ดู', ['view', 'id' => $model->student_id], ['class' => 'text-indigo-600 hover:text-indigo-900 font-medium']) ?>
                        <?= Html::a('แก้ไข', ['update', 'id' => $model->student_id], ['class' => 'text-amber-600 hover:text-amber-900 font-medium']) ?>
                        <?= Html::a('ลบ', ['delete', 'id' => $model->student_id], [
                            'class' => 'text-red-600 hover:text-red-900 font-medium',
                            'data' => ['confirm' => 'คุณต้องการลบรายการนี้?', 'method' => 'post'],
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($dataProvider->getTotalCount() == 0): ?>
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4">
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'options' => ['class' => 'flex space-x-1 justify-center'],
        'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50'],
        'activePageCssClass' => 'bg-indigo-600 text-white',
    ]) ?>
</div>
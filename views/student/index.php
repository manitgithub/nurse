<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'จัดการนักศึกษา';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">📚
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="text-sm text-gray-500 mt-1">รายการนักศึกษาทั้งหมด</p>
    </div>
    <?= Html::a('+ เพิ่มนักศึกษา', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>

<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รหัส</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">โรงเรียนมัธยม</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">GPAX มัธยม</th>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?= Html::encode($model->fullname) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= Html::encode($model->high_school) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= $model->gpax_hs ?>
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
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td>
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
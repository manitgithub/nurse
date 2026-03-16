<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'งานวิจัย';
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">🔬
        <?= Html::encode($this->title) ?>
    </h1>
    <?= Html::a('+ เพิ่มงานวิจัย', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>

<!-- ==================== SUMMARY CARDS ==================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-sm p-6 text-white text-center">
        <h3 class="text-lg font-medium opacity-90">จำนวนโครงการวิจัยทั้งหมด</h3>
        <p class="text-4xl font-bold mt-2"><?= number_format($totalProjects) ?></p>
        <p class="text-indigo-100 mt-1 text-sm">ผลงาน</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">งบประมาณรวม</h3>
        <p class="text-3xl font-bold text-indigo-600"><?= number_format($totalBudget, 2) ?></p>
        <p class="text-gray-500 mt-1 text-sm">บาท</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-3 border-b pb-2">สถานะงานวิจัย</h3>
        <div class="space-y-2">
            <?php foreach ($statusSummary as $status => $count): ?>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600"><?= Html::encode($status) ?></span>
                    <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-full text-xs"><?= $count ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ==================== FILTER SECTION ==================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'flex flex-col sm:flex-row gap-4 items-end']
    ]); ?>

    <div class="flex-1 w-full relative">
        <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">ค้นหางานวิจัย (ชื่อเรื่อง,
            ผู้แต่ง, แหล่งทุน, ปี)</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                🔍
            </span>
            <?= $form->field($searchModel, 'globalSearch')->textInput([
                'class' => 'w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition',
                'placeholder' => 'พิมพ์คำค้นหา...',
            ])->label(false) ?>
        </div>
    </div>

    <div class="w-full sm:w-48">
        <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">สถานะ</label>
        <?= $form->field($searchModel, 'work_status')->dropDownList([
            'ตีพิมพ์' => 'ตีพิมพ์',
            'อยู่ระหว่างดำเนินการ' => 'อยู่ระหว่างดำเนินการ',
            'เสร็จสิ้น' => 'เสร็จสิ้น',
        ], [
            'class' => 'w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition',
            'prompt' => 'ทั้งหมด'
        ])->label(false) ?>
    </div>

    <div>
        <?= Html::submitButton('กรองข้อมูล', ['class' => 'w-full sm:w-auto px-6 py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-lg hover:bg-indigo-100 border border-indigo-200 transition shadow-sm']) ?>
        <?= Html::a('ล้าง', ['index'], ['class' => 'w-full sm:w-auto px-4 py-2 bg-white text-gray-600 font-medium rounded-lg hover:bg-gray-50 border border-gray-300 transition shadow-sm ml-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ชื่อผลงาน</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">พ.ศ.</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">เจ้าของผลงาน</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">เผยแพร่ระดับ</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs">
                        <div class="truncate" title="<?= Html::encode($model->title) ?>">
                            <?= Html::encode($model->title) ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->year) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-[200px] truncate">
                        <?= Html::encode($model->authors) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= Html::encode($model->publish_level) ?: '-' ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <?php
                        $statusColor = 'bg-gray-100 text-gray-700';
                        if ($model->work_status === 'ตีพิมพ์') {
                            $statusColor = 'bg-green-100 text-green-700';
                        } elseif ($model->work_status === 'อยู่ระหว่างดำเนินการ') {
                            $statusColor = 'bg-blue-100 text-blue-700';
                        }
                        ?>
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $statusColor ?>">
                            <?= Html::encode($model->work_status) ?: '-' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <?= Html::a('ดู', ['view', 'id' => $model->id], ['class' => 'text-indigo-600 hover:text-indigo-900 font-medium']) ?>
                        <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-amber-600 hover:text-amber-900 font-medium']) ?>
                        <?= Html::a('ลบ', ['delete', 'id' => $model->id], ['class' => 'text-red-600 hover:text-red-900 font-medium', 'data' => ['confirm' => 'ลบข้อมูลนี้?', 'method' => 'post']]) ?>
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
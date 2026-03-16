<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'บันทึกรายจ่าย';
?>

<div class="transaction-form bg-gray-50 min-h-screen p-6">
    <div class="max-w-full mx-auto bg-white p-8 rounded-2xl shadow-xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4"><?= Html::encode($this->title) ?></h1>
        <div class="bg-blue-50 p-4 rounded-lg mb-8 border-l-4 border-blue-600">
            <p class="text-sm font-bold text-blue-800">หมวดงบประมาณ: <?= Html::encode($model->allocation->category->name) ?></p>
            <p class="text-xs text-blue-600 mt-1">ปีงบประมาณ: <?= $model->allocation->fiscal_year ?> | คงเหลือปัจจุบัน: <?= number_format($model->allocation->getRemainingBalance(), 2) ?></p>
        </div>

        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'space-y-6'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1'],
                'inputOptions' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 bg-gray-50 border'],
                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
            ],
        ]); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?= $form->field($model, 'transaction_date')->input('date') ?>
            <?= $form->field($model, 'requester')->textInput(['placeholder' => 'ระบุชื่อผู้เบิก']) ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?= $form->field($model, 'activity_name')->textInput(['placeholder' => 'ระบุชื่อกิจกรรม']) ?>
            <?= $form->field($model, 'subject_name')->textInput(['placeholder' => 'ระบุชื่อรายวิชา (ถ้ามี)']) ?>
        </div>

        <div class="pt-6 border-t">
            <div class="bg-yellow-50 p-4 rounded-lg mb-6 flex justify-between items-center px-6 border border-yellow-200">
                <span class="text-lg font-bold text-yellow-800">ยอดเสนอขออนุมัติ</span>
                <?= $form->field($model, 'proposed_amount', ['template' => '{input}', 'options' => ['class' => 'w-48']])->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'block w-full rounded-md border-yellow-300 text-right text-lg font-bold p-2']) ?>
            </div>

            <h2 class="text-lg font-bold text-gray-800 mb-4">รายละเอียดค่าใช้จ่ายจริง</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?= $form->field($model, 'cost_compensation')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'cost_accommodation')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'cost_materials')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'cost_hospitality')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'cost_transportation')->textInput(['type' => 'number', 'step' => '0.01']) ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t">
            <?= $form->field($model, 'reference_no')->textInput(['placeholder' => 'เลขที่อ้างอิง']) ?>
            <?= $form->field($model, 'note')->textarea(['rows' => 1]) ?>
        </div>

        <div class="flex justify-end gap-4 pt-8 border-t">
            <?= Html::a('ยกเลิก', ['transactions', 'allocation_id' => $model->allocation_id], ['class' => 'px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50']) ?>
            <?= Html::submitButton('บันทึกข้อมูล', ['class' => 'px-6 py-2 text-sm font-bold text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-all']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border';
?>
<div class="max-w-2xl mx-auto bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-4']]); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ปีการศึกษา <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'academic_year')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อนักเรียนทุน <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'scholar_name')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">ระดับคุณวุฒิ</label>
        <?= $form->field($model, 'qualification_id')->dropDownList($qualifications, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">สถาบัน</label>
            <?= $form->field($model, 'institution')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">สาขา</label>
            <?= $form->field($model, 'major')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันเริ่มต้น</label>
            <?= $form->field($model, 'start_date')->textInput(['class' => $inputClass . ' datepicker-be'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันสิ้นสุด</label>
            <?= $form->field($model, 'end_date')->textInput(['class' => $inputClass . ' datepicker-be'])->label(false) ?>
        </div>
    </div>
    <div class="flex justify-end space-x-3 pt-4 border-t">
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
        <?= Html::submitButton('💾 บันทึก', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
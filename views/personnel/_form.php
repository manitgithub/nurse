<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border';
?>
<div class="max-w-3xl mx-auto bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-4']]); ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">รหัสบุคลากร <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'personnel_code')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'fullname')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">เพศ</label>
            <?= $form->field($model, 'gender')->dropDownList(\app\models\Personnel::getGenderList(), ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
            <?= $form->field($model, 'email')->textInput(['class' => $inputClass, 'type' => 'email'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">โทรศัพท์</label>
            <?= $form->field($model, 'phone')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันเกิด</label>
            <?= $form->field($model, 'birth_date')->textInput(['class' => $inputClass, 'type' => 'date'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันเริ่มงาน</label>
            <?= $form->field($model, 'start_date')->textInput(['class' => $inputClass, 'type' => 'date'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันสิ้นสุดสัญญา</label>
            <?= $form->field($model, 'contract_end_date')->textInput(['class' => $inputClass, 'type' => 'date'])->label(false) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">คุณวุฒิ</label>
            <?= $form->field($model, 'qualification_id')->dropDownList($qualifications, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทสัญญา</label>
            <?= $form->field($model, 'contract_type_id')->dropDownList($contractTypes, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">แผนก</label>
            <?= $form->field($model, 'department_id')->dropDownList($departments, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
        <?= $form->field($model, 'status')->dropDownList([1 => 'ปฏิบัติงาน', 0 => 'ไม่ปฏิบัติงาน'], ['class' => $inputClass])->label(false) ?>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t">
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
        <?= Html::submitButton('💾 บันทึก', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
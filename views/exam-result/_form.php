<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border';
?>
<div class="max-w-4xl mx-auto bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-6']]); ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">นักศึกษา <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'student_id')->dropDownList($students, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">รอบสอบ <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'round_id')->dropDownList($rounds, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
    </div>

    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">ผลการสอบรายวิชา</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php for ($i = 1; $i <= 8; $i++):
            $attr = "subject_{$i}_score"; ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <?= \app\models\ExamResult::getSubjectLabels()["subject_{$i}"] ?>
                </label>
                <?= $form->field($model, $attr)->dropDownList(\app\models\ExamResult::getPassFailList(), ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
            </div>
        <?php endfor; ?>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
        <?= $form->field($model, 'status')->dropDownList(\app\models\ExamResult::getStatusList(), ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t">
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
        <?= Html::submitButton('💾 บันทึก', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
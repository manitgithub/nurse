<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Personnel;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Student $model */

?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-4']]); ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสนักศึกษา <span
                        class="text-red-500">*</span></label>
                <?= $form->field($model, 'student_id')->textInput(['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'maxlength' => true])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัส <span
                        class="text-red-500">*</span></label>
                <?= $form->field($model, 'batch')->textInput(['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'placeholder' => 'เช่น 69', 'maxlength' => true])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span
                        class="text-red-500">*</span></label>
                <?= $form->field($model, 'fullname')->textInput(['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'maxlength' => true])->label(false) ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">โรงเรียนมัธยม</label>
                <?= $form->field($model, 'high_school')->textInput(['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'maxlength' => true])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">GPAX มัธยม</label>
                <?= $form->field($model, 'gpax_hs')->textInput(['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'type' => 'number', 'step' => '0.01', 'min' => '0', 'max' => '4'])->label(false) ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ภูมิลำเนา</label>
                <?= $form->field($model, 'hometown')->textInput(['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'maxlength' => true])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                <?= $form->field($model, 'status')->dropDownList(\app\models\Student::getStatusList(), ['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'prompt' => '-- เลือก --'])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">อาจารย์ที่ปรึกษา</label>
                <?php
                $advisors = Personnel::find()->where(['track' => 'สาย ว'])->orderBy(['fullname' => SORT_ASC])->all();
                $advisorList = ArrayHelper::map($advisors, 'id', 'fullname');
                ?>
                <?= $form->field($model, 'advisor_id')->dropDownList($advisorList, ['class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border', 'prompt' => '-- เลือก --'])->label(false) ?>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t">
            <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
            <?= Html::submitButton('💾 บันทึก', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
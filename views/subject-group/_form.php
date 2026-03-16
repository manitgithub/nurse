<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SubjectGroup */
/* @var $form yii\widgets\ActiveForm */

$inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border';
?>

<div class="max-w-2xl mx-auto bg-white shadow-sm rounded-xl p-6 border border-gray-200">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-4']]); ?>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อสาขาตามโครงสร้าง <span
                class="text-red-500">*</span></label>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => $inputClass])->label(false) ?>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
        <?= $form->field($model, 'status')->dropDownList([1 => 'ใช้งาน', 0 => 'ยกเลิก'], ['class' => $inputClass])->label(false) ?>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t">
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
        <?= Html::submitButton('💾 บันทึก', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
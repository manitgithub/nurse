<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\StudentGrade $model */
/** @var array $students */

$this->title = $model->isNewRecord ? 'เพิ่มผลการเรียน' : 'แก้ไขผลการเรียน';
?>
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-3 mb-6">
        <?= Html::a('←', ['index'], ['class' => 'text-gray-400 hover:text-gray-600 text-2xl']) ?>
        <h1 class="text-2xl font-bold text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
    </div>

    <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-200">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1'],
                'inputOptions' => ['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition'],
                'errorOptions' => ['class' => 'text-sm text-red-600 mt-1'],
            ],
        ]); ?>

        <div class="grid grid-cols-1 gap-6">
            <?= $form->field($model, 'student_id')->dropDownList($students, ['prompt' => '-- เลือกนักศึกษา --']) ?>
            <?= $form->field($model, 'academic_year')->textInput(['placeholder' => 'เช่น 1 / 2568']) ?>
            <?= $form->field($model, 'gpax')->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0, 'max' => 4]) ?>
        </div>

        <div class="mt-8 flex justify-end space-x-3">
            <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
            <?= Html::submitButton('บันทึกข้อมูล', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
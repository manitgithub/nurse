<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'บันทึกการจัดสรรงบประมาณ' : 'แก้ไขการจัดสรรงบประมาณ';
?>

<div class="allocation-form bg-gray-50 min-h-screen p-6">
    <div class="max-w-full mx-auto bg-white p-8 rounded-2xl shadow-xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4"><?= Html::encode($this->title) ?></h1>
        <p class="text-gray-600 mb-8">หมวดกิจกรรม: <span class="font-bold text-blue-600"><?= Html::encode($model->category->name) ?></span></p>

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
            <?= $form->field($model, 'fiscal_year')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="space-y-4 pt-4 border-t">
            <h2 class="text-lg font-bold text-gray-700">จำนวนเงิน (Income)</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?= $form->field($model, 'allocated_amount')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'adjustment_reduction')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'adjustment_addition')->textInput(['type' => 'number', 'step' => '0.01']) ?>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-8 border-t">
            <?= Html::a('ยกเลิก', ['index', 'fiscal_year' => $model->fiscal_year], ['class' => 'px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50']) ?>
            <?= Html::submitButton('บันทึกข้อมูล', ['class' => 'px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

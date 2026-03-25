<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border';
?>
<div class="max-w-3xl mx-auto bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-4', 'enctype' => 'multipart/form-data']]); ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">รหัสบุคลากร <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'personnel_code')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
        <div class="md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งทางวิชาการ</label>
            <?= $form->field($model, 'academic_position')->dropDownList(\app\models\Personnel::getAcademicPositionList(), ['class' => $inputClass, 'prompt' => '-- ไม่มี --'])->label(false) ?>
        </div>
        <div class="md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งงาน</label>
            <?= $form->field($model, 'job_position')->dropDownList(\app\models\Personnel::getJobPositionList(), ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div class="md:col-span-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span
                    class="text-red-500">*</span></label>
            <?= $form->field($model, 'fullname')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">เพศ</label>
            <?= $form->field($model, 'gender')->dropDownList(\app\models\Personnel::getGenderList(), ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">สาย</label>
            <?= $form->field($model, 'track')->dropDownList(\app\models\Personnel::getTrackList(), ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
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


    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันเกิด</label>
            <?= $form->field($model, 'birth_date')->textInput(['class' => $inputClass . ' datepicker-be'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันเกษียณอายุ</label>
            <?= $form->field($model, 'retirement_date')->textInput(['class' => $inputClass . ' datepicker-be'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันเริ่มงาน</label>
            <?= $form->field($model, 'start_date')->textInput(['class' => $inputClass . ' datepicker-be'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">วันสิ้นสุดสัญญา</label>
            <?= $form->field($model, 'contract_end_date')->textInput(['class' => $inputClass . ' datepicker-be'])->label(false) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">คุณวุฒิ</label>
            <?= $form->field($model, 'qualification_id')->dropDownList($qualifications, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทสัญญา</label>
            <?= $form->field($model, 'contract_type_id')->dropDownList($contractTypes, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">สาขา</label>
            <?= $form->field($model, 'department_id')->dropDownList($departments, ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">สาขาตามโครงสร้าง</label>
            <?= $form->field($model, 'subject_group_id')->dropDownList($subjectGroups ?? [], ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
            <?= $form->field($model, 'status')->dropDownList([1 => 'ปฏิบัติงาน', 0 => 'ไม่ปฏิบัติงาน'], [
                'class' => $inputClass,
                'id' => 'personnel-status-dropdown'
            ])->label(false) ?>
        </div>
        <div id="resignation-year-container" class="<?= $model->status === 0 ? '' : 'hidden' ?>">
            <label class="block text-sm font-medium text-gray-700 mb-1">ปีที่ลาออก (พ.ศ.)</label>
            <?= $form->field($model, 'resignation_year')->textInput(['class' => $inputClass, 'placeholder' => 'เช่น 2566'])->label(false) ?>
        </div>
    </div>

    <?php
    $this->registerJs("
        $('#personnel-status-dropdown').on('change', function() {
            if ($(this).val() == '0') {
                $('#resignation-year-container').removeClass('hidden');
            } else {
                $('#resignation-year-container').addClass('hidden');
                $('#personnel-resignation_year').val('');
            }
        });
    ");
    ?>

    <!-- ความเชี่ยวชาญ -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">🔬 ความเชี่ยวชาญ
            (พิมพ์เพื่อค้นหาหรือเพิ่มใหม่)</label>
        <select id="personnel-expertises" name="PersonnelExpertise[]" multiple
            placeholder="เลือกหรือพิมพ์ความเชี่ยวชาญ..." class="w-full" autocomplete="off">
            <?php
            if (!isset($expertiseList)) {
                $expertiseList = [];
            }
            foreach ($expertiseList as $eid => $ename): ?>
                <option value="<?= $eid ?>" <?= in_array($eid, $selectedExpertises ?? []) ? 'selected' : '' ?>>
                    <?= Html::encode($ename) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 space-y-4">
        <h3 class="text-sm font-bold text-indigo-800 uppercase tracking-wider flex items-center">
            <span class="mr-2">🪪</span> ข้อมูลใบอนุญาตประกอบวิชาชีพ
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">เลขที่ใบอนุญาตฯ</label>
                <?= $form->field($model, 'license_no')->textInput(['class' => $inputClass])->label(false) ?>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">เลขที่สมาชิกสภาการพยาบาล</label>
                <?= $form->field($model, 'council_member_no')->textInput(['class' => $inputClass])->label(false) ?>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">วันหมดอายุใบอนุญาตฯ</label>
                <?= $form->field($model, 'license_expire_date')->textInput(['class' => $inputClass . ' datepicker-be'])->label(false) ?>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ใบอนุญาตประกอบวิชาชีพ
                    (ไฟล์แนบ)</label>
                <?= $form->field($model, 'license_file')->fileInput(['class' => 'text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100'])->label(false) ?>
                <?php if ($model->license_file): ?>
                    <div class="text-[10px] text-indigo-600 mt-1">ไฟล์ปัจจุบัน: <?= basename($model->license_file) ?></div>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">บัตรสมาชิก (ภาพ)</label>
                <?= $form->field($model, 'member_card_file')->fileInput(['class' => 'text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100'])->label(false) ?>
                <?php if ($model->member_card_file): ?>
                    <div class="text-[10px] text-gray-500 mt-1">ไฟล์ปัจจุบัน: <?= basename($model->member_card_file) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t">
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
        <?= Html::submitButton('💾 บันทึก', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerCssFile('https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs("
    if (typeof TomSelect !== 'undefined') {
        new TomSelect('#personnel-expertises', {
            create: true,
            plugins: ['remove_button'],
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });
    }
");
?>
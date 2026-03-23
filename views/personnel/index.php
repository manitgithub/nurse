<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'จัดการบุคลากร';
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">👥
        <?= Html::encode($this->title) ?>
    </h1>
    <?= Html::a('+ เพิ่มบุคลากร', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">บุคลากรทั้งหมด</p>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total']) ?></p>
    </div>
    <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 shadow-sm">
        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider mb-1">สายวิชาการ</p>
        <p class="text-2xl font-bold text-indigo-700"><?= number_format($stats['academic']) ?></p>
    </div>
    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 shadow-sm">
        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mb-1">สายปฏิบัติการ</p>
        <p class="text-2xl font-bold text-amber-700"><?= number_format($stats['operational']) ?></p>
    </div>
    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 shadow-sm">
        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">ปฏิบัติงาน</p>
        <p class="text-2xl font-bold text-emerald-700"><?= number_format($stats['active']) ?></p>
    </div>
    <div class="bg-rose-50 p-4 rounded-xl border border-rose-100 shadow-sm">
        <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wider mb-1">ไม่ปฏิบัติงาน</p>
        <p class="text-2xl font-bold text-rose-700"><?= number_format($stats['inactive']) ?></p>
    </div>
</div>

<!-- Filter Form -->
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
    <div class="flex items-center space-x-2 mb-4 border-b border-gray-100 pb-2">
        <span class="text-lg">🔍</span>
        <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">ตัวกรองข้อมูล</h2>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-x-4 gap-y-2 items-end']
    ]); ?>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">รหัสบุคลากร</label>
        <?= $form->field($searchModel, 'personnel_code')->textInput(['placeholder' => 'ค้นหา...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ชื่อ-นามสกุล</label>
        <?= $form->field($searchModel, 'fullname')->textInput(['placeholder' => 'ค้นหา...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สายงาน</label>
        <?= $form->field($searchModel, 'track')->dropDownList(\app\models\Personnel::getTrackList(), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ตำแหน่งงาน</label>
        <?= $form->field($searchModel, 'job_position')->dropDownList(\app\models\Personnel::getJobPositionList(), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สาขาวิชา</label>
        <?= $form->field($searchModel, 'department_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Department::find()->all(), 'id', 'name'), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สาขาตามโครงสร้าง</label>
        <?= $form->field($searchModel, 'subject_group_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\SubjectGroup::find()->all(), 'id', 'name'), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">สถานะ</label>
        <?= $form->field($searchModel, 'status')->dropDownList([1 => 'ปฏิบัติงาน', 0 => 'ไม่ปฏิบัติงาน'], ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">เพศ</label>
        <?= $form->field($searchModel, 'gender')->dropDownList(\app\models\Personnel::getGenderList(), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ตำแหน่งวิชาการ</label>
        <?= $form->field($searchModel, 'academic_position')->dropDownList(\app\models\Personnel::getAcademicPositionList(), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">คุณวุฒิ</label>
        <?= $form->field($searchModel, 'qualification_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Qualification::find()->all(), 'id', 'name'), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ประเภทสัญญา</label>
        <?= $form->field($searchModel, 'contract_type_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\ContractType::find()->all(), 'id', 'name'), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ปีที่ลาออก</label>
        <?= $form->field($searchModel, 'resignation_year')->textInput(['placeholder' => 'พ.ศ.', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ความเชี่ยวชาญ</label>
        <?= $form->field($searchModel, 'expertise_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Expertise::find()->orderBy('name')->all(), 'id', 'name'), ['prompt' => 'ทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2'])->label(false) ?>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ปฏิบัติงานปี (พ.ศ.)</label>
        <?php
        $currentYearBE = date('Y') + 543;
        $yearsBE = [];
        for ($i = 0; $i < 5; $i++) {
            $y = $currentYearBE - $i;
            $yearsBE[$y] = $y;
        }
        ?>
        <?= $form->field($searchModel, 'active_year_be')->dropDownList($yearsBE, ['prompt' => 'เลือกปี...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 font-bold text-indigo-600 border-indigo-200 bg-indigo-50/30'])->label(false) ?>
    </div>

    <div class="flex space-x-2">
        <?= Html::submitButton('🔍', ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition flex-1']) ?>
        <?= Html::a('ล้าง', ['index'], ['class' => 'bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<div x-data="{ activeTab: '<?= Yii::$app->request->get('p-page') ? 'operational' : 'academic' ?>' }">
    <!-- Tabs Navigation -->
    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl mb-6 max-w-md">
        <button @click="activeTab = 'academic'" 
            :class="activeTab === 'academic' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
            class="flex-1 py-2 px-4 rounded-lg text-sm font-bold transition flex items-center justify-center space-x-2">
            <span>📚 สาย ว (วิชาการ)</span>
            <span class="bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full text-[10px]"><?= number_format($academicDataProvider->getTotalCount()) ?></span>
        </button>
        <button @click="activeTab = 'operational'" 
            :class="activeTab === 'operational' ? 'bg-white shadow-sm text-amber-600' : 'text-gray-500 hover:text-gray-700'"
            class="flex-1 py-2 px-4 rounded-lg text-sm font-bold transition flex items-center justify-center space-x-2">
            <span>🛠️ สาย ป (ปฏิบัติการ)</span>
            <span class="bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full text-[10px]"><?= number_format($operationalDataProvider->getTotalCount()) ?></span>
        </button>
    </div>

    <!-- Academic Table -->
    <div x-show="activeTab === 'academic'" x-transition>
        <?= $this->render('_list_table', ['dataProvider' => $academicDataProvider]) ?>
    </div>

    <!-- Operational Table -->
    <div x-show="activeTab === 'operational'" x-transition x-cloak>
        <?= $this->render('_list_table', ['dataProvider' => $operationalDataProvider]) ?>
    </div>
</div>
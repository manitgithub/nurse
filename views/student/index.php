<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\StudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $stats */

$this->title = 'จัดการนักศึกษา';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">📚 <?= Html::encode($this->title) ?></h1>
        <p class="text-sm text-gray-500 mt-1">จัดการข้อมูลและติดตามสถานะนักศึกษา</p>
    </div>
    <div class="flex space-x-2">
        <?= Html::a('📊 ส่งออก Excel', ['export'] + Yii::$app->request->queryParams, ['class' => 'bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition flex items-center']) ?>
        <?= Html::a('+ เพิ่มนักศึกษา', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
    </div>
</div>

<!-- Tab Navigation -->
<div x-data="{ activeTab: 'students' }" class="space-y-6">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-1" aria-label="Tabs">
            <button @click="activeTab = 'students'"
                :class="activeTab === 'students' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="px-5 py-2.5 text-sm font-semibold border-b-2 rounded-t-lg transition-all">
                📋 ข้อมูลนักศึกษา
            </button>
            <button @click="activeTab = 'exam_stats'"
                :class="activeTab === 'exam_stats' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="px-5 py-2.5 text-sm font-semibold border-b-2 rounded-t-lg transition-all">
                📊 สถิติการสอบ
            </button>
        </nav>
    </div>

    <!-- Tab 1: Student Data -->
    <div x-show="activeTab === 'students'" x-transition>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">นักศึกษาทั้งหมด</p>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total']) ?></p>
            </div>
            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 shadow-sm">
                <p class="text-xs font-bold text-emerald-600 uppercase mb-1 font-bold">กำลังศึกษา</p>
                <p class="text-2xl font-bold text-emerald-700"><?= number_format($stats['active']) ?></p>
            </div>
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 shadow-sm">
                <p class="text-xs font-bold text-blue-600 uppercase mb-1 font-bold">สำเร็จการศึกษา</p>
                <p class="text-2xl font-bold text-blue-700"><?= number_format($stats['graduated']) ?></p>
            </div>
            <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 shadow-sm">
                <p class="text-xs font-bold text-amber-600 uppercase mb-1 font-bold">พักการเรียน</p>
                <p class="text-2xl font-bold text-amber-700"><?= number_format($stats['inactive']) ?></p>
            </div>
            <div class="bg-rose-50 p-4 rounded-xl border border-rose-100 shadow-sm">
                <p class="text-xs font-bold text-rose-600 uppercase mb-1 font-bold">พ้นสภาพ</p>
                <p class="text-2xl font-bold text-rose-700"><?= number_format($stats['dropped']) ?></p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'grid grid-cols-1 md:grid-cols-4 gap-4 items-end']
            ]); ?>

            <?= $form->field($searchModel, 'student_id', ['options' => ['class' => 'm-0']])->textInput(['placeholder' => 'ค้นหารหัสนักศึกษา...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('รหัสนักศึกษา', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>

            <?= $form->field($searchModel, 'fullname', ['options' => ['class' => 'm-0']])->textInput(['placeholder' => 'ค้นหาชื่อ-นามสกุล...', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('ชื่อ-นามสกุล', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>

            <div class="grid grid-cols-2 gap-2">
                <?= $form->field($searchModel, 'batch', ['options' => ['class' => 'm-0']])->dropDownList(\app\models\Student::getBatchList(), ['prompt' => 'ทุกรุ่น', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('รุ่น', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>

                <?= $form->field($searchModel, 'status', ['options' => ['class' => 'm-0']])->dropDownList(\app\models\Student::getStatusList(), ['prompt' => 'ทุกสถานะ', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('สถานะ', ['class' => 'block text-sm font-bold text-gray-700 mb-1']) ?>
            </div>

            <div>
                <?php
                $advisors = \app\models\Personnel::find()->where(['track' => 'สาย ว'])->orderBy(['fullname' => SORT_ASC])->all();
                $advisorList = \yii\helpers\ArrayHelper::map($advisors, 'id', 'fullname');
                echo $form->field($searchModel, 'advisor_id', ['options' => ['class' => 'm-0']])->dropDownList($advisorList, ['prompt' => 'อาจารย์ที่ปรึกษาทั้งหมด', 'class' => 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-2.5'])->label('อาจารย์ที่ปรึกษา', ['class' => 'block text-sm font-bold text-gray-700 mb-1']);
                ?>
            </div>

            <div class="flex space-x-2">
                <?= Html::submitButton('🔍 ค้นหา', ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition flex-1']) ?>
                <?= Html::a('ล้าง', ['index'], ['class' => 'bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
            </div>

            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>

        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รหัสนักศึกษา</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รุ่น</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">โรงเรียนมัธยม</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">GPAX มัธยม</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">อาจารย์ที่ปรึกษา</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ปีที่จบ</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($dataProvider->getModels() as $model): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                <?= Html::encode($model->student_id) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($model->batch): ?>
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">รหัส
                                        <?= Html::encode($model->batch) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= Html::encode($model->fullname) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= Html::encode($model->high_school) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $model->gpax_hs ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $model->advisor ? Html::encode($model->advisor->fullname) : '-' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $model->graduation_year ? $model->graduation_year . ' (พ.ศ.)' : '-' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = ['active' => 'green', 'inactive' => 'yellow', 'graduated' => 'blue', 'dropped' => 'red'];
                                $statusLabels = \app\models\Student::getStatusList();
                                $color = $statusColors[$model->status] ?? 'gray';
                                ?>
                                <span
                                    class="inline-flex items-center rounded-full bg-<?= $color ?>-100 px-2.5 py-0.5 text-xs font-medium text-<?= $color ?>-800">
                                    <?= $statusLabels[$model->status] ?? $model->status ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                <?= Html::a('ดู', ['view', 'id' => $model->student_id], ['class' => 'text-indigo-600 hover:text-indigo-900 font-medium']) ?>
                                <?= Html::a('แก้ไข', ['update', 'id' => $model->student_id], ['class' => 'text-amber-600 hover:text-amber-900 font-medium']) ?>
                                <?= Html::a('ลบ', ['delete', 'id' => $model->student_id], [
                                    'class' => 'text-red-600 hover:text-red-900 font-medium',
                                    'data' => ['confirm' => 'คุณต้องการลบรายการนี้?', 'method' => 'post'],
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($dataProvider->getTotalCount() == 0): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-400">ยังไม่มีข้อมูล</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options' => ['class' => 'flex space-x-1 justify-center'],
                'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50'],
                'activePageCssClass' => 'bg-indigo-600 text-white',
            ]) ?>
        </div>
    </div>

    <!-- Tab 2: Exam Statistics -->
    <div x-show="activeTab === 'exam_stats'" x-transition x-cloak>

        <!-- Year Selector -->
        <div class="flex items-center gap-3 mb-6">
            <label class="text-sm font-medium text-gray-600">เลือกปีที่จบ:</label>
            <select id="exam-year-select"
                onchange="window.location.href='<?= Url::to(['index']) ?>&exam_year='+this.value+'#exam_stats'"
                class="rounded-lg border-gray-300 bg-white px-4 py-2 pr-8 text-sm font-medium shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                <?php foreach ($examYears as $val => $label): ?>
                    <option value="<?= Html::encode($val) ?>" <?= $val == $examSelectedYear ? 'selected' : '' ?>>
                        <?= Html::encode($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">นักศึกษาทั้งหมด</div>
                <div class="text-3xl font-bold text-gray-900"><?= number_format($examTotalStudents) ?>
                    <span class="text-sm font-normal text-gray-400">คน</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-emerald-500">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">สอบผ่านครบทั้ง 8 วิชา</div>
                <div class="text-3xl font-bold text-emerald-600"><?= number_format($examOverallPassed) ?>
                    <span class="text-sm font-normal text-gray-400">คน</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-indigo-500">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">อัตราผ่านรวม</div>
                <div class="text-3xl font-bold text-indigo-600"><?= $examOverallPassRate ?>
                    <span class="text-sm font-normal text-gray-400">%</span>
                </div>
            </div>
        </div>

        <?php if (empty($examRoundStats)): ?>
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-gray-400 text-lg">ยังไม่มีข้อมูลผลสอบสำหรับรุ่นนี้</div>
            </div>
        <?php else: ?>

            <!-- Per-Round Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">📈 อัตราผ่าน/ไม่ผ่าน แต่ละรอบสอบ</h2>
                    <div style="position: relative; height: 300px;">
                        <canvas id="studentRoundChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">📋 สรุปแต่ละรอบสอบ</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-2 px-3 text-left text-xs font-semibold text-gray-500 uppercase">รอบสอบ</th>
                                    <th class="py-2 px-3 text-center text-xs font-semibold text-gray-500 uppercase">เข้าสอบ</th>
                                    <th class="py-2 px-3 text-center text-xs font-semibold text-gray-500 uppercase">ผ่าน</th>
                                    <th class="py-2 px-3 text-center text-xs font-semibold text-gray-500 uppercase">ไม่ผ่าน</th>
                                    <th class="py-2 px-3 text-center text-xs font-semibold text-gray-500 uppercase">อัตราผ่าน</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($examRoundStats as $rs): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-2.5 px-3 font-medium text-gray-800">
                                            ปี <?= Html::encode($rs['round']->year) ?> รอบ <?= Html::encode($rs['round']->round_number) ?>
                                        </td>
                                        <td class="py-2.5 px-3 text-center text-gray-600"><?= number_format($rs['exam_count']) ?></td>
                                        <td class="py-2.5 px-3 text-center">
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700"><?= number_format($rs['passed']) ?></span>
                                        </td>
                                        <td class="py-2.5 px-3 text-center">
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700"><?= number_format($rs['failed']) ?></span>
                                        </td>
                                        <td class="py-2.5 px-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500 rounded-full" style="width: <?= $rs['pass_rate'] ?>%"></div>
                                                </div>
                                                <span class="text-xs font-semibold text-gray-700"><?= $rs['pass_rate'] ?>%</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Per-Subject Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">📊 อัตราผ่านรายวิชา (รวมทุกรอบ)</h2>
                    <div style="position: relative; height: 350px;">
                        <canvas id="studentSubjectChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">📖 สรุปรายวิชา (รวมทุกรอบ)</h2>
                    <div class="space-y-3">
                        <?php foreach ($examSubjectStats as $key => $s): ?>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-medium text-gray-800"><?= Html::encode($s['label']) ?></span>
                                    <span class="text-xs font-bold <?= $s['pass_rate'] >= 60 ? 'text-emerald-600' : 'text-red-600' ?>"><?= $s['pass_rate'] ?>%</span>
                                </div>
                                <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 <?= $s['pass_rate'] >= 60 ? 'bg-emerald-500' : 'bg-red-400' ?>"
                                        style="width: <?= $s['pass_rate'] ?>%"></div>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-gray-500">ผ่าน <span class="font-semibold text-emerald-600"><?= number_format($s['pass']) ?></span></span>
                                    <span class="text-xs text-gray-500">ไม่ผ่าน <span class="font-semibold text-red-500"><?= number_format($s['fail']) ?></span></span>
                                    <span class="text-xs text-gray-500">รวม <span class="font-semibold text-gray-700"><?= number_format($s['total']) ?></span></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Detailed Per-Round × Per-Subject Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">📝 รายละเอียดรายวิชาแต่ละรอบสอบ</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="py-2 px-3 text-left text-xs font-semibold text-gray-500 uppercase sticky left-0 bg-white">วิชา</th>
                                <?php foreach ($examRoundStats as $rs): ?>
                                    <th colspan="2" class="py-2 px-3 text-center text-xs font-semibold text-gray-500 uppercase border-l border-gray-200">
                                        ปี <?= $rs['round']->year ?> รอบ <?= $rs['round']->round_number ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <th class="py-1 px-3 sticky left-0 bg-white"></th>
                                <?php foreach ($examRoundStats as $rs): ?>
                                    <th class="py-1 px-2 text-center text-xs text-emerald-600 font-medium border-l border-gray-200">ผ่าน</th>
                                    <th class="py-1 px-2 text-center text-xs text-red-500 font-medium">ไม่ผ่าน</th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php for ($i = 1; $i <= 8; $i++):
                                $key = "subject_{$i}";
                            ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-2.5 px-3 font-medium text-gray-800 whitespace-nowrap sticky left-0 bg-white"><?= Html::encode($subjectLabels[$key]) ?></td>
                                    <?php foreach ($examRoundStats as $rs): ?>
                                        <td class="py-2.5 px-2 text-center border-l border-gray-100">
                                            <span class="text-emerald-700 font-semibold"><?= $rs['subjects'][$key]['pass'] ?></span>
                                        </td>
                                        <td class="py-2.5 px-2 text-center">
                                            <span class="text-red-500 font-semibold"><?= $rs['subjects'][$key]['fail'] ?></span>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php if (!empty($examRoundStats)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let roundChartInit = false;
    let subjectChartInit = false;

    function initExamCharts() {
        if (roundChartInit) return;
        roundChartInit = true;

        const roundEl = document.getElementById('studentRoundChart');
        const subjectEl = document.getElementById('studentSubjectChart');
        if (!roundEl || !subjectEl) return;

        new Chart(roundEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_map(function ($rs) {
                    return "ปี {$rs['round']->year} รอบ {$rs['round']->round_number}";
                }, $examRoundStats)) ?>,
                datasets: [
                    {
                        label: 'ผ่าน',
                        data: <?= json_encode(array_map(fn($rs) => $rs['passed'], $examRoundStats)) ?>,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'ไม่ผ่าน',
                        data: <?= json_encode(array_map(fn($rs) => $rs['failed'], $examRoundStats)) ?>,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            afterBody: function(items) {
                                const idx = items[0].dataIndex;
                                const rates = <?= json_encode(array_map(fn($rs) => $rs['pass_rate'], $examRoundStats)) ?>;
                                return 'อัตราผ่าน: ' + rates[idx] + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, title: { display: true, text: 'จำนวน (คน)' } }
                }
            }
        });

        const subjectData = <?= json_encode(array_values(array_map(function ($s) {
            return ['label' => $s['label'], 'pass' => $s['pass'], 'fail' => $s['fail'], 'rate' => $s['pass_rate']];
        }, $examSubjectStats))) ?>;

        new Chart(subjectEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: subjectData.map(s => s.label),
                datasets: [
                    {
                        label: 'ผ่าน',
                        data: subjectData.map(s => s.pass),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'ไม่ผ่าน',
                        data: subjectData.map(s => s.fail),
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            afterBody: function(items) {
                                const idx = items[0].dataIndex;
                                return 'อัตราผ่าน: ' + subjectData[idx].rate + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: { beginAtZero: true, stacked: false, title: { display: true, text: 'จำนวน (ครั้ง)' } }
                }
            }
        });
    }

    // Init charts when tab is shown (Alpine x-show visibility)
    const observer = new MutationObserver(function() {
        const tab = document.getElementById('studentRoundChart');
        if (tab && tab.offsetParent !== null) {
            initExamCharts();
        }
    });
    observer.observe(document.body, { attributes: true, subtree: true, attributeFilter: ['style'] });

    // Also check if exam_stats tab is active on load (via URL hash)
    if (window.location.hash === '#exam_stats' || new URLSearchParams(window.location.search).has('exam_year')) {
        // Trigger Alpine to switch to exam_stats tab
        setTimeout(function() {
            const el = document.querySelector('[x-data]');
            if (el && el.__x) {
                el.__x.$data.activeTab = 'exam_stats';
            } else if (el && el._x_dataStack) {
                el._x_dataStack[0].activeTab = 'exam_stats';
            }
            setTimeout(initExamCharts, 100);
        }, 100);
    }
});
</script>
<?php endif; ?>
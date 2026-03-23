<?php
use yii\helpers\Html;

/** @var app\models\Student $model */
/** @var yii\data\ActiveDataProvider $gradesProvider */
/** @var yii\data\ActiveDataProvider $licenseProvider */
/** @var array $trend */
/** @var app\models\StudentGrade[] $history */

$this->title = $model->fullname;
$statusLabels = \app\models\Student::getStatusList();
?>

<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">👤
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->student_id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('🗑 ลบ', ['delete', 'id' => $model->student_id], [
            'class' => 'px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium shadow-sm',
            'data' => ['confirm' => 'คุณต้องการลบรายการนี้?', 'method' => 'post'],
        ]) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Student Info -->
    <div class="lg:col-span-2 bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">ข้อมูลนักศึกษา</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">รหัสนักศึกษา</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->student_id) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ชื่อ-นามสกุล</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->fullname) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">โรงเรียนมัธยม</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->high_school) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">GPAX มัธยม</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= $model->gpax_hs ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ภูมิลำเนา</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->hometown) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
                <dd class="text-sm mt-1">
                    <?= $statusLabels[$model->status] ?? $model->status ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ปีที่จบการศึกษา</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= $model->graduation_year ? $model->graduation_year . ' (พ.ศ.)' : '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">อาจารย์ที่ปรึกษา</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= $model->advisor ? Html::encode($model->advisor->fullname) : '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- Stats Card -->
    <div class="space-y-4">
        <div class="grid grid-cols-2 lg:grid-cols-1 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 shadow-sm rounded-xl p-6 text-white">
                <h3 class="text-sm font-medium opacity-80">GPA ล่าสุด</h3>
                <p class="text-3xl font-bold mt-1">
                    <?= $model->latestGrade ? number_format($model->latestGrade->gpax, 2) : 'N/A' ?>
                </p>
            </div>
            <div class="bg-gradient-to-br from-violet-500 to-fuchsia-600 shadow-sm rounded-xl p-6 text-white">
                <h3 class="text-sm font-medium opacity-80">GPA เฉลี่ยสะสม</h3>
                <p class="text-3xl font-bold mt-1">
                    <?= $model->averageGpax ? number_format($model->averageGpax, 2) : 'N/A' ?>
                </p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 shadow-sm rounded-xl p-6 text-white">
            <h3 class="text-sm font-medium opacity-80">ผลสอบใบอนุญาต</h3>
            <p class="text-3xl font-bold mt-1">
                <?= count($model->licenseExams) ?> ครั้ง
            </p>
        </div>

        <!-- Trend Analysis Card -->
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
            <h3 class="text-sm font-bold text-gray-500 uppercase mb-2">แนวโน้มผลการเรียน</h3>
            <div class="flex items-center space-x-2">
                <span
                    class="inline-flex items-center rounded-full bg-<?= $trend['color'] ?>-100 px-3 py-1 text-sm font-bold text-<?= $trend['color'] ?>-700">
                    <?= Html::encode($trend['label']) ?>
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-2"><?= Html::encode($trend['description']) ?></p>
        </div>
    </div>
</div>

<!-- GPA History Chart -->
<div class="mt-6 bg-white shadow-sm rounded-xl border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">📈 กราฟประวัติผลการเรียน (GPAX)</h2>
    <div class="h-[300px]">
        <canvas id="gpaxHistoryChart"></canvas>
    </div>
</div>

<!-- Grades History -->
<div class="mt-6 bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">📊 ประวัติผลการเรียน</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ปีการศึกษา</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">GPAX</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($gradesProvider->getModels() as $grade): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm text-gray-900">
                        <?php
                        $parts = explode('/', $grade->academic_year);
                        echo count($parts) == 2 ? Html::encode($parts[1] . '/' . $parts[0]) : Html::encode($grade->academic_year);
                        ?>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-900 font-medium">
                        <?= number_format($grade->gpax, 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($gradesProvider->getTotalCount() == 0): ?>
                <tr>
                    <td colspan="2" class="px-6 py-4 text-center text-gray-400 text-sm">ยังไม่มีข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<!-- Exam Results History -->
<div class="mt-6 bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">📝 ผลการสอบสภาฯ (จำลอง)</h2>
        <span class="text-xs text-gray-400">แสดงผลคะแนนแยกตามวิชา</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">รอบสอบ</th>
                    <?php
                    $subjectLabels = \app\models\ExamResult::getSubjectLabels();
                    for ($i = 1; $i <= 8; $i++): ?>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase"
                            title="<?= Html::encode($subjectLabels["subject_{$i}"]) ?>">
                            วิชา <?= $i ?>
                        </th>
                    <?php endfor; ?>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">สถานะ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($examResultsProvider->getModels() as $exam): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                            <?= $exam->round ? "ปี {$exam->round->year} รอบ {$exam->round->round_number}" : '-' ?>
                        </td>
                        <?php for ($i = 1; $i <= 8; $i++):
                            $attr = "subject_{$i}_score"; ?>
                            <td
                                class="px-3 py-3 text-sm text-center <?= (float) $exam->$attr >= 60 ? 'text-green-600 font-medium' : 'text-gray-400' ?>">
                                <?= $exam->$attr !== null ? number_format($exam->$attr, 0) : '-' ?>
                            </td>
                        <?php endfor; ?>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?= $exam->status === 'passed' ? 'bg-green-100 text-green-800' : ($exam->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') ?>">
                                <?= \app\models\ExamResult::getStatusList()[$exam->status] ?? $exam->status ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($examResultsProvider->getTotalCount() == 0): ?>
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-400 text-sm">ยังไม่มีข้อมูลผลสอบ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-2">
        <?php for ($i = 1; $i <= 8; $i++): ?>
            <div class="text-[10px] text-gray-500 truncate">
                <span class="font-bold">วิชา <?= $i ?>:</span> <?= Html::encode($subjectLabels["subject_{$i}"]) ?>
            </div>
        <?php endfor; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('gpaxHistoryChart').getContext('2d');

        // Process data for chart
        const labels = [];
        const data = [];

        <?php foreach ($history as $grade):
            $parts = explode('/', $grade->academic_year);
            $label = count($parts) == 2 ? $parts[1] . '/' . $parts[0] : $grade->academic_year;
            ?>
            labels.push('<?= $label ?>');
            data.push(<?= (float) $grade->gpax ?>);
        <?php endforeach; ?>

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'GPA เฉพาะเทอม',
                    data: data,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        max: 4,
                        ticks: { stepSize: 0.5 }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return 'GPA: ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
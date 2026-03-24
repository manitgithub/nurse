<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'สถิติการสอบ';
?>

<div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-900">📊 <?= Html::encode($this->title) ?></h1>
    <div class="flex items-center gap-3">
        <label class="text-sm font-medium text-gray-600">เลือกปีที่จบ:</label>
        <select id="year-select"
            onchange="window.location.href='<?= Url::to(['statistics']) ?>&graduation_year='+this.value"
            class="block rounded-lg border-gray-300 bg-white px-4 py-2 pr-8 text-sm font-medium shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
            <?php foreach ($years as $val => $label): ?>
                <option value="<?= Html::encode($val) ?>" <?= $val == $selectedYear ? 'selected' : '' ?>>
                    <?= Html::encode($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">นักศึกษาทั้งหมด</div>
        <div class="text-3xl font-bold text-gray-900"><?= number_format($totalStudents) ?>
            <span class="text-sm font-normal text-gray-400">คน</span>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-emerald-500">
        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">สอบผ่านครบทั้ง 8 วิชา</div>
        <div class="text-3xl font-bold text-emerald-600"><?= number_format($overallPassed) ?>
            <span class="text-sm font-normal text-gray-400">คน</span>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-indigo-500">
        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">อัตราผ่านรวม</div>
        <div class="text-3xl font-bold text-indigo-600"><?= $overallPassRate ?>
            <span class="text-sm font-normal text-gray-400">%</span>
        </div>
    </div>
</div>

<?php if (empty($roundStats)): ?>
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="text-gray-400 text-lg">ยังไม่มีข้อมูลผลสอบสำหรับรุ่นนี้</div>
    </div>
<?php else: ?>

    <!-- Section: Per-Round Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Bar Chart: Pass/Fail per Round -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📈 อัตราผ่าน/ไม่ผ่าน แต่ละรอบสอบ</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="roundChart"></canvas>
            </div>
        </div>

        <!-- Round Summary Table -->
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
                        <?php foreach ($roundStats as $rs): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-medium text-gray-800">
                                    ปี <?= Html::encode($rs['round']->year) ?> รอบ
                                    <?= Html::encode($rs['round']->round_number) ?>
                                </td>
                                <td class="py-2.5 px-3 text-center text-gray-600"><?= number_format($rs['exam_count']) ?></td>
                                <td class="py-2.5 px-3 text-center">
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700"><?= number_format($rs['passed']) ?></span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700"><?= number_format($rs['failed']) ?></span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full"
                                                style="width: <?= $rs['pass_rate'] ?>%"></div>
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

    <!-- Section: Per-Subject Stats (Aggregated) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Horizontal Bar Chart: Subject Pass Rate -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📊 อัตราผ่านรายวิชา (รวมทุกรอบ)</h2>
            <div style="position: relative; height: 350px;">
                <canvas id="subjectChart"></canvas>
            </div>
        </div>

        <!-- Subject Summary Table -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📖 สรุปรายวิชา (รวมทุกรอบ)</h2>
            <div class="space-y-3">
                <?php foreach ($subjectStats as $key => $s): ?>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-gray-800"><?= Html::encode($s['label']) ?></span>
                            <span
                                class="text-xs font-bold <?= $s['pass_rate'] >= 60 ? 'text-emerald-600' : 'text-red-600' ?>"><?= $s['pass_rate'] ?>%</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 <?= $s['pass_rate'] >= 60 ? 'bg-emerald-500' : 'bg-red-400' ?>"
                                style="width: <?= $s['pass_rate'] ?>%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-gray-500">ผ่าน <span
                                    class="font-semibold text-emerald-600"><?= number_format($s['pass']) ?></span></span>
                            <span class="text-xs text-gray-500">ไม่ผ่าน <span
                                    class="font-semibold text-red-500"><?= number_format($s['fail']) ?></span></span>
                            <span class="text-xs text-gray-500">รวม <span
                                    class="font-semibold text-gray-700"><?= number_format($s['total']) ?></span></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Section: Detailed Per-Round × Per-Subject Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📝 รายละเอียดรายวิชาแต่ละรอบสอบ</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th
                            class="py-2 px-3 text-left text-xs font-semibold text-gray-500 uppercase sticky left-0 bg-white">
                            วิชา</th>
                        <?php foreach ($roundStats as $rs): ?>
                            <th colspan="2"
                                class="py-2 px-3 text-center text-xs font-semibold text-gray-500 uppercase border-l border-gray-200">
                                ปี <?= $rs['round']->year ?> รอบ <?= $rs['round']->round_number ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <th class="py-1 px-3 sticky left-0 bg-white"></th>
                        <?php foreach ($roundStats as $rs): ?>
                            <th class="py-1 px-2 text-center text-xs text-emerald-600 font-medium border-l border-gray-200">ผ่าน
                            </th>
                            <th class="py-1 px-2 text-center text-xs text-red-500 font-medium">ไม่ผ่าน</th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php for ($i = 1; $i <= 8; $i++):
                        $key = "subject_{$i}";
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-2.5 px-3 font-medium text-gray-800 whitespace-nowrap sticky left-0 bg-white">
                                <?= Html::encode($subjectLabels[$key]) ?>
                            </td>
                            <?php foreach ($roundStats as $rs): ?>
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

<?php if (!empty($roundStats)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Round Chart — Grouped Bar
            const roundCtx = document.getElementById('roundChart').getContext('2d');
            new Chart(roundCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_map(function ($rs) {
                        return "ปี {$rs['round']->year} รอบ {$rs['round']->round_number}";
                    }, $roundStats)) ?>,
                    datasets: [
                        {
                            label: 'ผ่าน',
                            data: <?= json_encode(array_map(fn($rs) => $rs['passed'], $roundStats)) ?>,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            borderRadius: 4,
                        },
                        {
                            label: 'ไม่ผ่าน',
                            data: <?= json_encode(array_map(fn($rs) => $rs['failed'], $roundStats)) ?>,
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
                                afterBody: function (items) {
                                    const idx = items[0].dataIndex;
                                    const rates = <?= json_encode(array_map(fn($rs) => $rs['pass_rate'], $roundStats)) ?>;
                                    return 'อัตราผ่าน: ' + rates[idx] + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            title: { display: true, text: 'จำนวน (คน)' }
                        }
                    }
                }
            });

            // Subject Chart — Horizontal Bar
            const subjectCtx = document.getElementById('subjectChart').getContext('2d');
            const subjectData = <?= json_encode(array_values(array_map(function ($s) {
                return ['label' => $s['label'], 'pass' => $s['pass'], 'fail' => $s['fail'], 'rate' => $s['pass_rate']];
            }, $subjectStats))) ?>;

            new Chart(subjectCtx, {
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
                                afterBody: function (items) {
                                    const idx = items[0].dataIndex;
                                    return 'อัตราผ่าน: ' + subjectData[idx].rate + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            stacked: false,
                            title: { display: true, text: 'จำนวน (ครั้ง)' }
                        }
                    }
                }
            });
        });
    </script>
<?php endif; ?>
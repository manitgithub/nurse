<?php

use yii\helpers\Html;
use yii\helpers\Json;

/** @var yii\web\View $this */
$this->title = 'แดชบอร์ด — ระบบสถิติสำนักพยาบาล';
?>

<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">📊 แดชบอร์ดสรุปสถิติ</h1>
        <p class="text-gray-500 mt-1">ภาพรวมระบบสถิติและจัดการข้อมูลสำนักพยาบาล</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">นักศึกษาทั้งหมด</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalStudents ?></p>
                    <p class="text-sm text-green-600 mt-1">กำลังศึกษา: <?= $activeStudents ?></p>
                </div>
                <div class="bg-indigo-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" /></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">บุคลากรทั้งหมด</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalPersonnel ?></p>
                    <p class="text-sm text-green-600 mt-1">ปฏิบัติงาน: <?= $activePersonnel ?></p>
                </div>
                <div class="bg-emerald-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">นักเรียนทุน</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalScholarships ?></p>
                    <p class="text-sm text-blue-600 mt-1">ทุนทั้งหมด</p>
                </div>
                <div class="bg-amber-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493" /></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">GPA เฉลี่ย <?= $latestYear ? "(ปี $latestYear)" : '' ?></p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= number_format($avgGpax, 2) ?></p>
                    <p class="text-sm text-purple-600 mt-1">ผลการเรียนปัจจุบัน</p>
                </div>
                <div class="bg-purple-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABS ===== -->
    <div x-data="{ activeTab: 'students' }">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-1 -mb-px" role="tablist">
                <button @click="activeTab = 'students'" :class="activeTab === 'students' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">📚</span> นักศึกษา
                </button>
                <button @click="activeTab = 'personnel'" :class="activeTab === 'personnel' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">👥</span> บุคลากร
                </button>
                <button @click="activeTab = 'scholarship'" :class="activeTab === 'scholarship' ? 'border-amber-500 text-amber-600 bg-amber-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">🎓</span> ทุนการศึกษา
                </button>
            </nav>
        </div>

        <!-- ==================== TAB: นักศึกษา ==================== -->
        <div x-show="activeTab === 'students'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Retention Doughnut -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">อัตราการคงอยู่ของนักศึกษา</h2>
                    <div class="flex items-center">
                        <div class="w-1/2"><canvas id="retentionChart"></canvas></div>
                        <div class="w-1/2 pl-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>กำลังศึกษา</span>
                                <span class="font-semibold"><?= $activeStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>สำเร็จการศึกษา</span>
                                <span class="font-semibold"><?= $graduatedStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>พักการเรียน</span>
                                <span class="font-semibold"><?= $inactiveStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>พ้นสภาพ</span>
                                <span class="font-semibold"><?= $droppedStudents ?></span>
                            </div>
                            <div class="pt-3 border-t">
                                <p class="text-sm text-gray-500">อัตราการคงอยู่</p>
                                <p class="text-2xl font-bold text-emerald-600"><?= $retentionRate ?>%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GPAX Trend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">ผลการเรียนเฉลี่ยรายปี (GPAX)</h2>
                    <canvas id="gpaxChart"></canvas>
                </div>
            </div>

            <!-- License Exam & Recruitment -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- License Exam -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">สอบใบอนุญาตประกอบวิชาชีพ</h2>
                    <div class="flex items-center">
                        <div class="w-1/3"><canvas id="licenseChart"></canvas></div>
                        <div class="w-2/3 pl-6">
                            <p class="text-sm text-gray-500">สอบผ่าน <?= $licensePassed ?> / ทั้งหมด <?= $licenseTotal ?> คน</p>
                            <p class="text-4xl font-bold mt-2 <?= $licenseRate >= 50 ? 'text-emerald-600' : 'text-red-600' ?>"><?= $licenseRate ?>%</p>
                            <p class="text-sm text-gray-400 mt-1">อัตราสอบผ่าน</p>
                        </div>
                    </div>
                </div>

                <!-- Recruitment Plan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">แผนอัตรากำลัง</h2>
                    <?php if ($latestPlan): ?>
                    <p class="text-sm text-gray-500 mb-3">ปีงบประมาณ <?= Html::encode($latestPlan->fiscal_year) ?></p>
                    <div class="flex justify-between items-end mb-3">
                        <div><p class="text-xs text-gray-500 uppercase">อัตราที่ได้รับ</p><p class="text-2xl font-bold text-gray-900"><?= $latestPlan->quota_amount ?></p></div>
                        <div><p class="text-xs text-gray-500 uppercase">บรรจุแล้ว</p><p class="text-2xl font-bold text-blue-600"><?= $latestPlan->recruited_amount ?></p></div>
                        <div><p class="text-xs text-gray-500 uppercase">คงเหลือ</p><p class="text-2xl font-bold <?= $remainingQuota > 0 ? 'text-emerald-600' : 'text-red-600' ?>"><?= $remainingQuota ?></p></div>
                    </div>
                    <?php $pct = $latestPlan->quota_amount > 0 ? round($latestPlan->recruited_amount / $latestPlan->quota_amount * 100) : 0; ?>
                    <div class="w-full bg-gray-200 rounded-full h-3"><div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-3 rounded-full" style="width: <?= min($pct, 100) ?>%"></div></div>
                    <p class="text-sm text-gray-500 text-right mt-1"><?= $pct ?>% บรรจุแล้ว</p>
                    <?php else: ?>
                    <p class="text-gray-400 text-sm">ยังไม่มีข้อมูลแผนอัตรากำลัง</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: บุคลากร ==================== -->
        <div x-show="activeTab === 'personnel'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display:none">

            <!-- Row 1: Department & Contract Type -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏢 บุคลากรแยกตามแผนก</h3>
                    <?php if (!empty($personnelByDept)): ?>
                        <canvas id="deptChart" height="220"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📄 บุคลากรแยกตามประเภทสัญญา</h3>
                    <?php if (!empty($personnelByContract)): ?>
                        <canvas id="contractChart" height="220"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Row 2: Qualification & Certification -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📜 บุคลากรแยกตามคุณวุฒิ</h3>
                    <?php if (!empty($personnelByQualification)): ?>
                    <div class="flex items-center">
                        <div class="w-1/2"><canvas id="qualChart"></canvas></div>
                        <div class="w-1/2 pl-4 space-y-2">
                            <?php
                            $qualColors = ['#6366f1','#8b5cf6','#a78bfa','#c4b5fd','#ddd6fe','#ede9fe'];
                            foreach ($personnelByQualification as $i => $row):
                                $color = $qualColors[$i % count($qualColors)];
                            ?>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background:<?= $color ?>"></span><span class="text-gray-700 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span></span>
                                <span class="font-semibold text-gray-900 ml-2"><?= $row['total'] ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏅 ใบรับรองแยกตามระดับ</h3>
                    <?php if (!empty($certByLevel)): ?>
                        <canvas id="certChart" height="220"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Row 3: Expertise -->
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🔬 ความเชี่ยวชาญยอดนิยม (Top 10)</h3>
                    <?php if (!empty($topExpertises)): ?>
                        <canvas id="expertiseChart" height="120"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: ทุนการศึกษา ==================== -->
        <div x-show="activeTab === 'scholarship'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display:none">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Summary Card -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-8 text-white">
                    <h3 class="text-lg font-medium opacity-90">นักเรียนทุนทั้งหมด</h3>
                    <p class="text-5xl font-bold mt-3"><?= $totalScholarships ?></p>
                    <p class="text-amber-100 mt-2 text-sm">จำนวนทุนที่บันทึกในระบบ</p>
                    <a href="<?= \yii\helpers\Url::to(['/scholarship/index']) ?>" class="inline-block mt-4 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition">ดูรายละเอียด →</a>
                </div>

                <!-- Scholarship by Qualification -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🎓 นักเรียนทุนแยกตามระดับคุณวุฒิ</h3>
                    <?php if (!empty($scholarByQualification)): ?>
                    <div class="space-y-3">
                        <?php
                        $maxScholar = max(array_column($scholarByQualification, 'total'));
                        $barColors = ['bg-amber-500','bg-orange-500','bg-yellow-500','bg-rose-500','bg-pink-500'];
                        foreach ($scholarByQualification as $i => $row):
                            $pctS = $maxScholar > 0 ? round($row['total'] / $maxScholar * 100) : 0;
                            $barColor = $barColors[$i % count($barColors)];
                        ?>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 font-medium"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                <span class="text-gray-900 font-bold"><?= $row['total'] ?> คน</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="<?= $barColor ?> h-3 rounded-full transition-all duration-500" style="width:<?= $pctS ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- end tabs -->
</div>

<!-- Alpine.js for tabs -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var chartColors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#06b6d4','#f43f5e','#84cc16','#14b8a6','#a855f7'];

    // ===== STUDENT TAB CHARTS =====

    // Retention Doughnut
    new Chart(document.getElementById('retentionChart'), {
        type: 'doughnut',
        data: {
            labels: ['กำลังศึกษา','สำเร็จการศึกษา','พักการเรียน','พ้นสภาพ'],
            datasets: [{
                data: [<?= $activeStudents ?>,<?= $graduatedStudents ?>,<?= $inactiveStudents ?>,<?= $droppedStudents ?>],
                backgroundColor: ['#10b981','#3b82f6','#f59e0b','#ef4444'],
                borderWidth: 0, hoverOffset: 8,
            }]
        },
        options: { cutout: '65%', plugins: { legend: { display: false } }, responsive: true }
    });

    // GPAX Line
    new Chart(document.getElementById('gpaxChart'), {
        type: 'line',
        data: {
            labels: <?= Json::encode(array_keys($gpaxByYear)) ?>,
            datasets: [{
                label: 'GPAX เฉลี่ย',
                data: <?= Json::encode(array_values($gpaxByYear)) ?>,
                borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.1)',
                tension: 0.4, fill: true, pointBackgroundColor: '#6366f1', pointRadius: 5, pointHoverRadius: 7,
            }]
        },
        options: { responsive: true, scales: { y: { min: 0, max: 4, ticks: { stepSize: 0.5 } } }, plugins: { legend: { display: false } } }
    });

    // License Doughnut
    new Chart(document.getElementById('licenseChart'), {
        type: 'doughnut',
        data: {
            labels: ['สอบผ่าน','ยังไม่ผ่าน'],
            datasets: [{ data: [<?= $licensePassed ?>,<?= max(0, $licenseTotal - $licensePassed) ?>], backgroundColor: ['#10b981','#e5e7eb'], borderWidth: 0 }]
        },
        options: { cutout: '70%', plugins: { legend: { display: false } }, responsive: true }
    });

    // ===== PERSONNEL TAB CHARTS =====

    <?php if (!empty($personnelByDept)): ?>
    new Chart(document.getElementById('deptChart'), {
        type: 'bar',
        data: {
            labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByDept)) ?>,
            datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int)$r['total'], $personnelByDept)) ?>, backgroundColor: chartColors, borderRadius: 8, borderSkipped: false }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    <?php endif; ?>

    <?php if (!empty($personnelByContract)): ?>
    new Chart(document.getElementById('contractChart'), {
        type: 'bar',
        data: {
            labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByContract)) ?>,
            datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int)$r['total'], $personnelByContract)) ?>, backgroundColor: ['#6366f1','#8b5cf6','#a78bfa','#c4b5fd','#ddd6fe'], borderRadius: 8, borderSkipped: false }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    <?php endif; ?>

    <?php if (!empty($personnelByQualification)): ?>
    new Chart(document.getElementById('qualChart'), {
        type: 'doughnut',
        data: {
            labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByQualification)) ?>,
            datasets: [{ data: <?= Json::encode(array_map(fn($r) => (int)$r['total'], $personnelByQualification)) ?>, backgroundColor: ['#6366f1','#8b5cf6','#a78bfa','#c4b5fd','#ddd6fe','#ede9fe'], borderWidth: 0, hoverOffset: 6 }]
        },
        options: { cutout: '60%', plugins: { legend: { display: false } }, responsive: true }
    });
    <?php endif; ?>

    <?php if (!empty($certByLevel)): ?>
    new Chart(document.getElementById('certChart'), {
        type: 'bar',
        data: {
            labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $certByLevel)) ?>,
            datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int)$r['total'], $certByLevel)) ?>, backgroundColor: ['#f59e0b','#f97316','#ef4444','#ec4899','#8b5cf6'], borderRadius: 8, borderSkipped: false }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    <?php endif; ?>

    <?php if (!empty($topExpertises)): ?>
    new Chart(document.getElementById('expertiseChart'), {
        type: 'bar',
        data: {
            labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $topExpertises)) ?>,
            datasets: [{ label: 'จำนวนบุคลากร', data: <?= Json::encode(array_map(fn($r) => (int)$r['total'], $topExpertises)) ?>, backgroundColor: chartColors, borderRadius: 8, borderSkipped: false }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    <?php endif; ?>
});
</script>
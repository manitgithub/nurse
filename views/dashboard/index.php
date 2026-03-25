<?php

use yii\helpers\Html;
use yii\helpers\Json;

/** @var yii\web\View $this */
$this->title = 'แดชบอร์ด — ระบบสถิติสำนักพยาบาล';

// Leaflet Assets
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [
    'integrity' => 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=',
    'crossorigin' => '',
]);
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [
    'integrity' => 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=',
    'crossorigin' => '',
]);
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
                    <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">บุคลากร (ปฏิบัติงาน)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $activePersonnel ?></p>
                    <div class="flex space-x-3 text-sm mt-1">
                        <span class="text-indigo-600 font-medium">สาย ว: <?= $activeAcademic ?></span>
                        <span class="text-emerald-600 font-medium">สาย ป: <?= $activeOperational ?></span>
                    </div>
                </div>
                <div class="bg-emerald-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
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
                    <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">ผลการเรียน (GPAX)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="kpiAvgGpax"><?= number_format($avgGpax, 2) ?></p>
                    <div class="flex space-x-3 text-xs mt-1">
                        <span class="text-emerald-600">สูงสุด: <span id="kpiMaxGpax"><?= number_format($maxGpax, 2) ?></span></span>
                        <span class="text-rose-600">ต่ำสุด: <span id="kpiMinGpax"><?= number_format($minGpax, 2) ?></span></span>
                    </div>
                </div>
                <div class="bg-purple-100 rounded-xl p-3">
                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABS ===== -->
    <div x-data="{ 
        activeTab: 'students',
        initMaps() {
            if (this.activeTab === 'research' && window.researchMap) {
                setTimeout(() => window.researchMap.invalidateSize(), 100);
            }
            if (this.activeTab === 'academic_service' && window.academicServiceMap) {
                setTimeout(() => window.academicServiceMap.invalidateSize(), 150);
            }
            if (this.activeTab === 'innovation' && window.innovationYearlyChart) {
                setTimeout(() => window.innovationYearlyChart.resize(), 100);
            }
            if (this.activeTab === 'scholarship' && window.scholarshipRetentionChart) {
                setTimeout(() => window.scholarshipRetentionChart.resize(), 100);
            }
            if (this.activeTab === 'budget' && window.budgetComparisonChart) {
                setTimeout(() => window.budgetComparisonChart.resize(), 100);
            }
        }
    }" x-init="$watch('activeTab', value => initMaps())">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-1 -mb-px" role="tablist">
                <button @click="activeTab = 'students'"
                    :class="activeTab === 'students' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">📚</span> นักศึกษา
                </button>
                <button @click="activeTab = 'personnel'"
                    :class="activeTab === 'personnel' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">👥</span> บุคลากร
                </button>
                <button @click="activeTab = 'scholarship'"
                    :class="activeTab === 'scholarship' ? 'border-amber-500 text-amber-600 bg-amber-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">🎓</span> นักเรียนทุน
                </button>
                <button @click="activeTab = 'research'"
                    :class="activeTab === 'research' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">🔬</span> งานวิจัย
                </button>
                <button @click="activeTab = 'academic_service'"
                    :class="activeTab === 'academic_service' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">🤝</span> บริการวิชาการ/ทำนุบำรุงวัฒนธรรม
                </button>
                <button @click="activeTab = 'innovation'"
                    :class="activeTab === 'innovation' ? 'border-purple-500 text-purple-600 bg-purple-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">💡</span> นวัตกรรม
                </button>
                <button @click="activeTab = 'budget'"
                    :class="activeTab === 'budget' ? 'border-rose-500 text-rose-600 bg-rose-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center px-5 py-3 border-b-2 font-semibold text-sm rounded-t-lg transition-all duration-200">
                    <span class="mr-2 text-lg">💰</span> รายรับ-รายจ่าย
                </button>
            </nav>
        </div>

        <!-- ==================== TAB: นักศึกษา ==================== -->
        <div x-show="activeTab === 'students'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" x-data="{ 
                globalBatch: 'total',
                gpaxModalOpen: false,
                gpaxModalRangeTitle: '',
                currentGpaxStudents: [],
                showGpaxStudents(rangeKey, rangeTitle) {
                    this.gpaxModalRangeTitle = rangeTitle;
                    // Always show data matching current global filter
                    let batchKey = this.globalBatch === 'total' ? 'total' : this.globalBatch;
                    
                    console.log('Loading GPAX Students. Batch:', batchKey, 'Range:', rangeKey);
                    console.log('Global Object Array:', window.globalAllGpaxStudents);

                    if (window.globalAllGpaxStudents && window.globalAllGpaxStudents[batchKey] && window.globalAllGpaxStudents[batchKey][rangeKey]) {
                        this.currentGpaxStudents = window.globalAllGpaxStudents[batchKey][rangeKey];
                        console.log('Matched Students:', this.currentGpaxStudents);
                    } else {
                        console.log('No match found in globalAllGpaxStudents');
                        this.currentGpaxStudents = [];
                    }
                    this.gpaxModalOpen = true;
                }
            }">

            <!-- Global Filter Row -->
            <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-indigo-700 font-bold">🔍 ตัวกรองข้อมูลนักศึกษา:</span>
                    <select x-model="globalBatch" @change="updateAllStudentCharts(globalBatch)"
                        class="text-sm border-indigo-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-2">
                        <option value="total">ทุกรหัส (ภาพรวม)</option>
                        <?php foreach (array_keys($gpaxTrendByBatch) as $b): ?>
                            <option value="<?= Html::encode($b) ?>">รหัส <?= Html::encode($b) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-indigo-600 text-sm italic" x-show="globalBatch !== 'total'">
                    กำลังแสดงข้อมูลเฉพาะ <span class="font-bold">รหัส <span x-text="globalBatch"></span></span>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Retention Doughnut -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">อัตราการคงอยู่ของนักศึกษา</h2>
                    <div class="flex items-center">
                        <div class="w-1/2"><canvas id="retentionChart"></canvas></div>
                        <div class="w-1/2 pl-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>กำลังศึกษา</span>
                                <span class="font-semibold" id="retentionCountActive"><?= $activeStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>สำเร็จการศึกษา</span>
                                <span class="font-semibold"
                                    id="retentionCountGraduated"><?= $graduatedStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>พักการเรียน</span>
                                <span class="font-semibold" id="retentionCountInactive"><?= $inactiveStudents ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center"><span
                                        class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>พ้นสภาพ</span>
                                <span class="font-semibold" id="retentionCountDropped"><?= $droppedStudents ?></span>
                            </div>
                            <div class="pt-3 border-t">
                                <p class="text-sm text-gray-500">อัตราการคงอยู่</p>
                                <p class="text-2xl font-bold text-emerald-600"><span
                                        id="retentionRateVal"><?= $retentionRate ?></span>%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GPAX Trend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">แนวโน้มผลการเรียนเฉลี่ย (GPAX)</h2>
                    <div class="h-[250px]">
                        <canvas id="gpaxChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- GPAX Grouping Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">การกระจายเกรดเฉลี่ยสะสม</h2>
                    <div class="flex items-center justify-around">
                        <div class="w-1/2 h-[220px]">
                            <canvas id="gpaxGroupChart"></canvas>
                        </div>
                        <div class="w-1/2 space-y-4 text-left pl-8">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 3.50 - 4.00</p>
                                <p class="text-2xl font-bold text-emerald-600 cursor-pointer hover:underline"
                                    id="gpaxCountR1" @click="showGpaxStudents('r1', '3.50 - 4.00')">
                                    <?= $gpaxGroups['r1'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 3.00 - 3.49</p>
                                <p class="text-2xl font-bold text-indigo-600 cursor-pointer hover:underline"
                                    id="gpaxCountR2" @click="showGpaxStudents('r2', '3.00 - 3.49')">
                                    <?= $gpaxGroups['r2'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 2.50 - 2.99</p>
                                <p class="text-2xl font-bold text-amber-500 cursor-pointer hover:underline"
                                    id="gpaxCountR3" @click="showGpaxStudents('r3', '2.50 - 2.99')">
                                    <?= $gpaxGroups['r3'] ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">เกรด 2.00 - 2.49</p>
                                <p class="text-2xl font-bold text-rose-600 cursor-pointer hover:underline"
                                    id="gpaxCountR4" @click="showGpaxStudents('r4', '2.00 - 2.49')">
                                    <?= $gpaxGroups['r4'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Web Modal for GPAX Students -->
                <div x-show="gpaxModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="gpaxModalOpen" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                            @click="gpaxModalOpen = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>
                        <div x-show="gpaxModalOpen" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    รายชื่อนักศึกษาเกณฑ์ <span x-text="gpaxModalRangeTitle"
                                        class="text-indigo-600"></span>
                                </h3>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:p-6 max-h-96 overflow-y-auto">
                                <ul class="divide-y divide-gray-200">
                                    <template x-for="student in currentGpaxStudents" :key="student.id">
                                        <li class="py-3 flex justify-between items-center bg-white rounded-lg px-4 mb-2 shadow-sm border border-gray-100 hover:bg-indigo-50 transition cursor-pointer"
                                            @click="window.location.href = '<?= yii\helpers\Url::to(['student/view']) ?>&id=' + student.id">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-900"
                                                    x-text="student.id"></span>
                                                <span class="text-sm text-gray-500" x-text="student.name"></span>
                                            </div>
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </li>
                                    </template>
                                    <li x-show="currentGpaxStudents.length === 0"
                                        class="text-sm text-gray-500 text-center py-4">ไม่พบข้อมูล</li>
                                </ul>
                            </div>
                            <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="gpaxModalOpen = false">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GPAX by Batch Bar Chart (Now potentially highlightable or filtered) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">เปรียบเทียบ GPAX ทุกรหัส (ค่าเฉลี่ยทุกเทอม)
                    </h2>
                    <div class="h-[250px]">
                        <canvas id="gpaxBatchChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- License Exam Row -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">สอบใบอนุญาตประกอบวิชาชีพ</h2>
                <div class="flex items-center">
                    <div class="w-1/4 h-[150px]"><canvas id="licenseChart"></canvas></div>
                    <div class="w-3/4 pl-12 flex items-center justify-around">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 uppercase">สอบผ่าน</p>
                            <p class="text-3xl font-bold text-emerald-600"><?= $licensePassed ?></p>
                        </div>
                        <div class="text-center border-l border-gray-100 pl-12">
                            <p class="text-sm text-gray-500 uppercase">ทั้งหมด</p>
                            <p class="text-3xl font-bold text-gray-900"><?= $licenseTotal ?></p>
                        </div>
                        <div class="text-center border-l border-gray-100 pl-12">
                            <p class="text-sm text-gray-500 uppercase">อัตราการสอบผ่าน</p>
                            <p class="text-4xl font-bold text-indigo-600"><?= $licenseRate ?>%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: บุคลากร ==================== -->
        <div x-show="activeTab === 'personnel'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

            <!-- Recruitment Plan (All Years) - Moved from Students -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">📋 แผนอัตรากำลัง (ทุกปีงบประมาณ)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php if (!empty($allPlans)): ?>
                        <?php foreach ($allPlans as $plan): ?>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-gray-800">ปี <?= Html::encode($plan->fiscal_year) ?></span>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $plan->getRemainingQuota() > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?>">
                                        <?= $plan->getRemainingQuota() > 0 ? 'ว่าง ' . $plan->getRemainingQuota() : 'เต็ม' ?>
                                    </span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>บรรจุ <?= $plan->recruited_amount ?>/<?= $plan->quota_amount ?></span>
                                    <span><?= $plan->quota_amount > 0 ? round($plan->recruited_amount / $plan->quota_amount * 100) : 0 ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <?php $pct = $plan->quota_amount > 0 ? min(100, round($plan->recruited_amount / $plan->quota_amount * 100)) : 0; ?>
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full py-6 text-center text-gray-400 italic">ยังไม่มีข้อมูลแผนอัตรากำลัง</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 5-Year Personnel Retention -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนบุคลากรที่มีอัตราคงอยู่ย้อนหลัง 5 ปี</h3>
                <div class="h-[250px]">
                    <canvas id="personnelRetentionChart"></canvas>
                </div>
            </div>

            <!-- Row 1: Department & Contract Type -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏢 บุคลากรแยกตามสาขา</h3>
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
                                $qualColors = ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe'];
                                foreach ($personnelByQualification as $i => $row):
                                    $color = $qualColors[$i % count($qualColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="flex items-center"><span class="w-3 h-3 rounded-full mr-2 flex-shrink-0"
                                                style="background:<?= $color ?>"></span><span
                                                class="text-gray-700 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span></span>
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

            <!-- Row 3: Track & Academic Position -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">👥 สายปฏิบัติการ / สายวิชาการ (ป/ว)</h3>
                    <?php if (!empty($personnelByTrack)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2"><canvas id="trackChart"></canvas></div>
                            <div class="w-1/2 pl-4 space-y-2">
                                <?php
                                $trackColors = ['#f59e0b', '#3b82f6']; // Amber and Blue
                                foreach ($personnelByTrack as $i => $row):
                                    $color = $trackColors[$i % count($trackColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="flex items-center"><span class="w-3 h-3 rounded-full mr-2 flex-shrink-0"
                                                style="background:<?= $color ?>"></span><span
                                                class="text-gray-700 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span></span>
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🎓 ตำแหน่งทางวิชาการ</h3>
                    <?php if (!empty($personnelByAcademicPosition)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2">
                                <canvas id="academicPosChart"></canvas>
                            </div>
                            <div class="w-1/2 pl-4 space-y-2" id="academicPosLegend">
                                <?php
                                $totalPos = array_sum(array_column($personnelByAcademicPosition, 'total'));
                                $posColors = ['#ec4899', '#f43f5e', '#f472b6', '#fb7185', '#fda4af', '#fecdd3'];
                                foreach ($personnelByAcademicPosition as $i => $row):
                                    $color = $posColors[$i % count($posColors)];
                                    $pct = $totalPos > 0 ? round(($row['total'] / $totalPos) * 100, 1) : 0;
                                    ?>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="flex items-center">
                                            <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background:<?= $color ?>"></span>
                                            <span class="text-gray-700 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                        </span>
                                        <div class="text-right">
                                            <span class="font-semibold text-gray-900"><?= $row['total'] ?></span>
                                            <span class="text-xs text-gray-400 ml-1">(<?= $pct ?>%)</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Row 4: Job Position & Expertise -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">💼 ตำแหน่งงาน</h3>
                    <?php if (!empty($personnelByJobPosition)): ?>
                        <canvas id="jobPosChart" height="200"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🔬 ความเชี่ยวชาญยอดนิยม (Top 10)</h3>
                    <?php if (!empty($topExpertises)): ?>
                        <canvas id="expertiseChart" height="200"></canvas>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: งานวิจัย ==================== -->
        <div x-show="activeTab === 'research'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

            <!-- Research Map -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📍 แผนที่โครงการวิจัย</h3>
                <div id="researchMap" class="w-full h-[600px] rounded-lg border border-gray-200 z-0"></div>
            </div>

            <!-- Research Summary Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Research Projects -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">🔬 โครงการวิจัย</h3>
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold">ทั้งหมด
                            <?= $totalResearch ?></span>
                    </div>
                    <?php if (!empty($researchByStatus)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2"><canvas id="researchStatusChart" height="200"></canvas></div>
                            <div class="w-1/2 pl-6 space-y-3">
                                <?php
                                $statusColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                                foreach ($researchByStatus as $i => $row):
                                    $color = $statusColors[$i % count($statusColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="flex items-center">
                                            <span class="w-3 h-3 rounded-full mr-2" style="background:<?= $color ?>"></span>
                                            <span
                                                class="text-gray-600 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                        </span>
                                        <span class="font-bold text-gray-900 ml-2"><?= $row['total'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <!-- Funding Sources & Total Budget -->
                <div class="space-y-6 flex flex-col">
                    <div
                        class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white flex-1 flex flex-col justify-center">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณวิจัยรวม</h3>
                        <p class="text-5xl font-bold mt-2"><?= number_format($totalResearchBudget, 2) ?></p>
                        <p class="text-indigo-100 mt-1 text-sm">บาท (จากทุกโครงการ)</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 แหล่งทุนวิจัยยอดนิยม (TOP 5)</h3>
                        <?php if (!empty($researchByFunding)): ?>
                            <canvas id="researchFundingChart" height="150"></canvas>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Yearly Comparison Section -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Project Count Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนโครงการวิจัยรายปี</h3>
                        <div class="h-[300px]">
                            <canvas id="researchYearlyProjectChart"></canvas>
                        </div>
                    </div>
                    <!-- Budget Sum Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 งบประมาณวิจัยรายปี</h3>
                        <div class="h-[300px]">
                            <canvas id="researchYearlyBudgetChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Summary Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">📊 ตารางสรุปเปรียบเทียบรายปี</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">ปีงบประมาณ</th>
                                    <th class="px-6 py-4">จำนวนโครงการ</th>
                                    <th class="px-6 py-4 text-right">งบประมาณรวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($researchStats as $row): ?>
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปีงบประมาณ
                                            <?= Html::encode($row['label']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                <?= number_format($row['total_projects']) ?> โครงการ
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-indigo-600 text-right">
                                            <?= number_format($row['total_budget'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-gray-50/50 font-bold">
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">รวมทั้งหมด</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">
                                        <?= number_format($totalResearch) ?> โครงการ
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-700 text-right border-t border-gray-100">
                                        <?= number_format($totalResearchBudget, 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>
        </div>

        <!-- ==================== TAB: บริการวิชาการ/ทำนุบำรุงวัฒนธรรม ==================== -->
        <div x-show="activeTab === 'academic_service'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

            <!-- Academic Service Map -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📍 แผนที่บริการวิชาการ/ทำนุบำรุงวัฒนธรรม</h3>
                <div id="academicServiceMap" class="w-full h-[600px] rounded-lg border border-gray-200 z-0"></div>
            </div>

            <!-- Academic Service Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">🤝 กิจกรรมบริการวิชาการ/ทำนุบำรุงวัฒนธรรม</h3>
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">ทั้งหมด
                            <?= $totalAcademicService ?></span>
                    </div>
                    <?php if (!empty($academicServiceByStatus)): ?>
                        <div class="flex items-center">
                            <div class="w-1/2"><canvas id="academicServiceStatusChart" height="200"></canvas></div>
                            <div class="w-1/2 pl-6 space-y-3">
                                <?php
                                $statusColors = ['#059669', '#34d399', '#6ee7b7', '#a7f3d0', '#ecfdf5'];
                                foreach ($academicServiceByStatus as $i => $row):
                                    $color = $statusColors[$i % count($statusColors)];
                                    ?>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="flex items-center">
                                            <span class="w-3 h-3 rounded-full mr-2" style="background:<?= $color ?>"></span>
                                            <span
                                                class="text-gray-600 truncate"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                        </span>
                                        <span class="font-bold text-gray-900 ml-2"><?= $row['total'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>

                <div class="space-y-6 flex flex-col">
                    <div
                        class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg p-6 text-white flex-1 flex flex-col justify-center">
                        <h3 class="text-lg font-medium opacity-90">จำนวนผู้เข้ารับบริการรวม</h3>
                        <p class="text-5xl font-bold mt-2"><?= number_format($totalParticipants) ?></p>
                        <p class="text-emerald-100 mt-1 text-sm">คน (จากทุกโครงการ)</p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white flex-1 flex flex-col justify-center">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณโครงการรวม</h3>
                        <p class="text-5xl font-bold mt-2"><?= number_format($totalAcademicBudget, 2) ?></p>
                        <p class="text-blue-100 mt-1 text-sm">บาท (จากทุกโครงการ)</p>
                    </div>

                    <a href="<?= \yii\helpers\Url::to(['/academic-service/index']) ?>"
                        class="inline-block px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-sm font-medium text-white transition text-center shadow-md">
                        รายละเอียดบริการวิชาการ →
                    </a>
                </div>
            </div>


            <!-- Yearly Comparison Section -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Project Count Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈
                            จำนวนโครงการบริการวิชาการ/ทำนุบำรุงวัฒนธรรมรายปี</h3>
                        <div class="h-[300px]">
                            <canvas id="academicYearlyProjectChart"></canvas>
                        </div>
                    </div>
                    <!-- Budget Sum Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">💰
                            งบประมาณบริการวิชาการ/ทำนุบำรุงวัฒนธรรมรายปี
                        </h3>
                        <div class="h-[300px]">
                            <canvas id="academicYearlyBudgetChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Summary Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">📊 ตารางสรุปเปรียบเทียบรายปี</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">ปีงบประมาณ</th>
                                    <th class="px-6 py-4">จำนวนโครงการ</th>
                                    <th class="px-6 py-4 text-right">งบประมาณรวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($academicServiceStats as $row): ?>
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปีงบประมาณ
                                            <?= Html::encode($row['label']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <?= number_format($row['total_projects']) ?> โครงการ
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 text-right">
                                            <?= number_format($row['total_budget'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-gray-50/50 font-bold">
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">รวมทั้งหมด</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 border-t border-gray-100">
                                        <?= number_format($totalAcademicService) ?> โครงการ
                                    </td>
                                    <td class="px-6 py-4 text-sm text-emerald-700 text-right border-t border-gray-100">
                                        <?= number_format($totalAcademicBudget, 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== TAB: นวัตกรรม ==================== -->
        <div x-show="activeTab === 'innovation'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6" style="display:none">

                <!-- Innovation Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Total Card -->
                    <div
                        class="bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl shadow-lg p-6 text-white flex flex-col justify-center">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-medium opacity-90">💡 นวัตกรรมทั้งหมด</h3>
                        </div>
                        <p class="text-6xl font-bold mt-2 text-center"><?= number_format($totalInnovation) ?></p>
                        <p class="text-purple-100 mt-3 text-sm text-center">ผลงานนวัตกรรมที่ถูกบันทึก</p>
                        <a href="<?= \yii\helpers\Url::to(['/innovation/index']) ?>"
                            class="mt-6 inline-block w-full px-4 py-2 bg-purple-700 hover:bg-purple-800 rounded-lg text-sm font-medium text-white transition text-center shadow-md">
                            ดูรายการนวัตกรรมทั้งหมด →
                        </a>
                    </div>

                    <!-- Yearly Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนนวัตกรรมรายปี</h3>
                        <div class="h-[250px] w-full relative">
                            <?php if (!empty($innovationStats)): ?>
                                <canvas id="innovationYearlyChart"></canvas>
                            <?php else: ?>
                                <p
                                    class="text-gray-400 text-sm text-center py-8 absolute inset-0 flex items-center justify-center">
                                    ยังไม่มีข้อมูลสถิติรายปี</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Detailed Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Advisors Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">🏆 อาจารย์ที่ปรึกษายอดนิยม (TOP 5)</h3>
                        <div class="h-[250px] w-full relative flex-1">
                            <?php if (!empty($topInnovationAdvisors)): ?>
                                <canvas id="innovationAdvisorChart"></canvas>
                            <?php else: ?>
                                <p
                                    class="text-gray-400 text-sm text-center py-8 absolute inset-0 flex items-center justify-center">
                                    ยังไม่มีข้อมูลอาจารย์ที่ปรึกษา</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Summary Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-800">📊 ตารางสรุปเปรียบเทียบรายปี</h3>
                        </div>
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-left h-full">
                                <thead class="bg-gray-50">
                                    <tr
                                        class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="px-6 py-4">ปี</th>
                                        <th class="px-6 py-4 text-right">จำนวนนวัตกรรม (ผลงาน)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($innovationStats as $row): ?>
                                        <tr class="hover:bg-gray-50/70 transition-colors">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปี
                                                <?= Html::encode($row['label']) ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 text-right">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <?= number_format($row['total_projects']) ?> ผลงาน
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50/50 font-bold mt-auto border-t-2 border-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">รวมทั้งหมด</td>
                                        <td class="px-6 py-4 text-sm text-purple-700 text-right">
                                            <?= number_format($totalInnovation) ?> ผลงาน
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== TAB: นักเรียนทุน ==================== -->
            <div x-show="activeTab === 'scholarship'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-6" style="display:none">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Summary Card -->
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-8 text-white">
                        <h3 class="text-lg font-medium opacity-90">นักเรียนทุนทั้งหมด</h3>
                        <p class="text-5xl font-bold mt-3"><?= $totalScholarships ?></p>
                        <p class="text-amber-100 mt-2 text-sm">จำนวนทุนที่บันทึกในระบบ</p>
                        <a href="<?= \yii\helpers\Url::to(['/scholarship/index']) ?>"
                            class="inline-block mt-4 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition">ดูรายละเอียด
                            →</a>
                    </div>

                    <!-- Scholarship by Qualification -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">🎓 นักเรียนทุนแยกตามระดับคุณวุฒิ</h3>
                        <?php if (!empty($scholarByQualification)): ?>
                            <div class="space-y-3">
                                <?php
                                $maxScholar = max(array_column($scholarByQualification, 'total'));
                                $barColors = ['bg-amber-500', 'bg-orange-500', 'bg-yellow-500', 'bg-rose-500', 'bg-pink-500'];
                                foreach ($scholarByQualification as $i => $row):
                                    $pctS = $maxScholar > 0 ? round($row['total'] / $maxScholar * 100) : 0;
                                    $barColor = $barColors[$i % count($barColors)];
                                    ?>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span
                                                class="text-gray-700 font-medium"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                            <div class="text-right">
                                                <span class="text-gray-900 font-bold"><?= $row['total'] ?> คน</span>
                                                <span class="text-xs text-amber-600 ml-1">(<?= $totalScholarships > 0 ? round($row['total'] / $totalScholarships * 100, 1) : 0 ?>%)</span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-3">
                                            <div class="<?= $barColor ?> h-3 rounded-full transition-all duration-500"
                                                style="width:<?= $pctS ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 5-Year Scholarship Retention -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 จำนวนนักเรียนทุนย้อนหลัง 5 ปี (ตามอายุสัญญา)</h3>
                    <div class="h-[300px]">
                        <canvas id="scholarshipRetentionChart"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Scholarship by Major -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">🏫 นักเรียนทุนแยกตามสาขา</h3>
                        <?php if (!empty($scholarByMajor)): ?>
                            <div class="space-y-3">
                                <?php
                                $maxMajor = max(array_column($scholarByMajor, 'total'));
                                $majorColors = ['bg-indigo-500', 'bg-purple-500', 'bg-blue-500', 'bg-cyan-500', 'bg-teal-500'];
                                foreach ($scholarByMajor as $i => $row):
                                    $pctM = $maxMajor > 0 ? round($row['total'] / $maxMajor * 100) : 0;
                                    $barColor = $majorColors[$i % count($majorColors)];
                                    ?>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span
                                                class="text-gray-700 font-medium"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                            <div class="text-right">
                                                <span class="text-gray-900 font-bold"><?= $row['total'] ?> คน</span>
                                                <span class="text-xs text-indigo-600 ml-1">(<?= $totalScholarships > 0 ? round($row['total'] / $totalScholarships * 100, 1) : 0 ?>%)</span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-3">
                                            <div class="<?= $barColor ?> h-3 rounded-full transition-all duration-500"
                                                style="width:<?= $pctM ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-8">ยังไม่มีข้อมูล</p>
                        <?php endif; ?>
                    </div>

                    <!-- Scholarship by Institution -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">🏛️ นักเรียนทุนแยกตามสถาบัน</h3>
                        <?php if (!empty($scholarByInstitution)): ?>
                            <div class="space-y-3">
                                <?php
                                $maxInst = max(array_column($scholarByInstitution, 'total'));
                                $instColors = ['bg-emerald-500', 'bg-green-500', 'bg-lime-500', 'bg-yellow-500', 'bg-amber-500'];
                                foreach ($scholarByInstitution as $i => $row):
                                    $pctI = $maxInst > 0 ? round($row['total'] / $maxInst * 100) : 0;
                                    $barColor = $instColors[$i % count($instColors)];
                                    ?>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span
                                                class="text-gray-700 font-medium"><?= Html::encode($row['label'] ?: 'ไม่ระบุ') ?></span>
                                            <div class="text-right">
                                                <span class="text-gray-900 font-bold"><?= $row['total'] ?> คน</span>
                                                <span class="text-xs text-emerald-600 ml-1">(<?= $totalScholarships > 0 ? round($row['total'] / $totalScholarships * 100, 1) : 0 ?>%)</span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-3">
                                            <div class="<?= $barColor ?> h-3 rounded-full transition-all duration-500"
                                                style="width:<?= $pctI ?>%"></div>
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

            <!-- ==================== TAB: รายรับ-รายจ่าย ==================== -->
            <div x-show="activeTab === 'budget'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-6" style="display:none">

                <!-- Budget Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณที่จัดสรร (ปี <?= $latestBudgetYear ?>)</h3>
                        <p class="text-4xl font-black mt-2"><?= number_format($currentYearTotalBudget, 2) ?></p>
                        <p class="text-indigo-100 mt-1 text-xs opacity-80">บาท (Net Budget)</p>
                    </div>
                    <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณที่เบิกจ่าย (ปี <?= $latestBudgetYear ?>)</h3>
                        <p class="text-4xl font-black mt-2"><?= number_format($currentYearTotalSpent, 2) ?></p>
                        <p class="text-rose-100 mt-1 text-xs opacity-80">บาท (Actual Expenses)</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-medium opacity-90">งบประมาณคงเหลือ (ปี <?= $latestBudgetYear ?>)</h3>
                        <p class="text-4xl font-black mt-2"><?= number_format($currentYearTotalBudget - $currentYearTotalSpent, 2) ?></p>
                        <p class="text-emerald-100 mt-1 text-xs opacity-80">บาท (Remaining Balance)</p>
                    </div>
                </div>

                <!-- Budget vs Expense Chart -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 เปรียบเทียบรายรับ-รายจ่ายรายปี</h3>
                        <div class="h-[350px]">
                            <canvas id="budgetComparisonChart"></canvas>
                        </div>
                    </div>

                    <!-- Current Year Activity Breakdown -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">📊 สัดส่วนการใช้จ่ายรายกิจกรรม (ปี <?= $latestBudgetYear ?>)</h3>
                            <span class="text-xs font-bold text-gray-400">เรียงตาม % การใช้จ่าย</span>
                        </div>
                        <div class="flex-1 overflow-y-auto max-h-[350px] pr-2 space-y-5">
                            <?php if (!empty($currentYearBreakdown)): ?>
                                <?php foreach ($currentYearBreakdown as $item): ?>
                                    <div class="group">
                                        <div class="flex justify-between items-end mb-1.5">
                                            <div class="flex-1 mr-4">
                                                <h4 class="text-sm font-bold text-gray-700 leading-tight group-hover:text-indigo-600 transition truncate" title="<?= Html::encode($item['category']) ?>">
                                                    <?= Html::encode($item['category']) ?>
                                                </h4>
                                                <p class="text-[10px] text-gray-400 mt-0.5">
                                                    ใช้ไป <?= number_format($item['spent'], 2) ?> / <?= number_format($item['total'], 2) ?> บาท
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-black <?= $item['percent'] > 90 ? 'text-rose-600' : ($item['percent'] > 50 ? 'text-indigo-600' : 'text-emerald-600') ?>">
                                                    <?= $item['percent'] ?>%
                                                </span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <?php 
                                            $barColor = 'bg-emerald-500';
                                            if ($item['percent'] > 90) $barColor = 'bg-rose-500';
                                            elseif ($item['percent'] > 50) $barColor = 'bg-indigo-500';
                                            ?>
                                            <div class="<?= $barColor ?> h-full rounded-full transition-all duration-1000 ease-out" style="width: <?= min(100, $item['percent']) ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 italic py-10">
                                    <svg class="w-12 h-12 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    ยังไม่มีข้อมูลการใช้จ่ายในปีนี้
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Yearly Budget Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">📊 ตารางสรุปงบประมาณรายปี</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">ปีงบประมาณ</th>
                                    <th class="px-6 py-4 text-right">งบประมาณสุทธิ (บาท)</th>
                                    <th class="px-6 py-4 text-right">เบิกจ่ายจริง (บาท)</th>
                                    <th class="px-6 py-4 text-right">คงเหลือ (บาท)</th>
                                    <th class="px-6 py-4 text-right">% การเบิกจ่าย</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($budgetStats as $row): ?>
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">ปีงบประมาณ <?= Html::encode($row['label']) ?></td>
                                        <td class="px-6 py-4 text-sm font-bold text-indigo-600 text-right"><?= number_format($row['total_budget'], 2) ?></td>
                                        <td class="px-6 py-4 text-sm font-bold text-rose-600 text-right"><?= number_format($row['total_spent'], 2) ?></td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 text-right"><?= number_format($row['total_budget'] - $row['total_spent'], 2) ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <?= $row['total_budget'] > 0 ? round(($row['total_spent'] / $row['total_budget']) * 100, 2) : 0 ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- end tabs -->
    </div>

    <!-- Alpine.js for tabs -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var chartColors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#f43f5e', '#84cc16', '#14b8a6', '#a855f7'];

            // ===== STUDENT TAB CHARTS =====

            // Retention Doughnut
            var retentionCtx = document.getElementById('retentionChart').getContext('2d');
            window.retentionChart = new Chart(retentionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['กำลังศึกษา', 'สำเร็จการศึกษา', 'พักการเรียน', 'พ้นสภาพ'],
                    datasets: [{
                        data: [<?= $activeStudents ?>, <?= $graduatedStudents ?>, <?= $inactiveStudents ?>, <?= $droppedStudents ?>],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderWidth: 0, hoverOffset: 8,
                    }]
                },
                options: { 
                    cutout: '65%', 
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.parsed || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }, 
                    responsive: true 
                }
            });

            // GPAX Line
            var gpaxChartCtx = document.getElementById('gpaxChart').getContext('2d');
            window.gpaxChart = new Chart(gpaxChartCtx, {
                type: 'line',
                data: {
                    labels: <?= Json::encode(array_keys($gpaxByYear)) ?>,
                    datasets: [{
                        label: 'GPAX เฉลี่ยรวม',
                        data: <?= Json::encode(array_values($gpaxByYear)) ?>,
                        borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.1)',
                        tension: 0.4, fill: true, pointBackgroundColor: '#6366f1', pointRadius: 5, pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { min: 0, max: 4, ticks: { stepSize: 0.5 } } },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let val = context.parsed.y;
                                    let pct = ((val / 4.0) * 100).toFixed(1);
                                    return 'GPAX: ' + val.toFixed(2) + ' (' + pct + '% of 4.0)';
                                }
                            }
                        }
                    }
                }
            });

            // GPAX Grouping Doughnut
            var gpaxGroupCtx = document.getElementById('gpaxGroupChart').getContext('2d');
            window.gpaxGroupChart = new Chart(gpaxGroupCtx, {
                type: 'doughnut',
                data: {
                    labels: ['3.50-4.00', '3.00-3.49', '2.50-2.99', '2.00-2.49'],
                    datasets: [{
                        data: [<?= $gpaxGroups['r1'] ?>, <?= $gpaxGroups['r2'] ?>, <?= $gpaxGroups['r3'] ?>, <?= $gpaxGroups['r4'] ?>],
                        backgroundColor: ['#10b981', '#6366f1', '#f59e0b', '#f43f5e'],
                        hoverOffset: 4,
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.parsed || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // GPAX by Batch Bar
            var gpaxBatchCtx = document.getElementById('gpaxBatchChart').getContext('2d');
            window.gpaxBatchChart = new Chart(gpaxBatchCtx, {
                type: 'bar',
                data: {
                    labels: <?= Json::encode(array_map(fn($b) => "รหัส $b", array_keys($gpaxByBatch))) ?>,
                    datasets: [{
                        label: 'GPAX เฉลี่ย',
                        data: <?= Json::encode(array_values($gpaxByBatch)) ?>,
                        backgroundColor: <?= Json::encode(array_map(fn($b) => '#8b5cf6', array_keys($gpaxByBatch))) ?>,
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 30,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let val = context.parsed.y;
                                    let pct = ((val / 4.0) * 100).toFixed(1);
                                    return 'GPAX: ' + val.toFixed(2) + ' (' + pct + '% of 4.0)';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, max: 4, ticks: { stepSize: 0.5 } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Data for dynamic updates
            var totalGpaxTrend = {
                labels: <?= Json::encode(array_keys($gpaxByYear)) ?>,
                data: <?= Json::encode(array_values($gpaxByYear)) ?>
            };
            var batchGpaxTrend = <?= Json::encode($gpaxTrendByBatch) ?>;

            var totalGpaxGroups = {
                r1: <?= $gpaxGroups['r1'] ?>,
                r2: <?= $gpaxGroups['r2'] ?>,
                r3: <?= $gpaxGroups['r3'] ?>,
                r4: <?= $gpaxGroups['r4'] ?>
            };
            var batchGpaxGroups = <?= Json::encode($gpaxGroupsByBatch) ?>;

            var totalRetention = {
                active: <?= $activeStudents ?>,
                graduated: <?= $graduatedStudents ?>,
                inactive: <?= $inactiveStudents ?>,
                dropped: <?= $droppedStudents ?>,
                rate: <?= $retentionRate ?>
            };
            var batchRetention = <?= Json::encode($retentionByBatch) ?>;

            var batchList = <?= Json::encode(array_keys($gpaxByBatch)) ?>;
            var totalGpaxStats = { avg: <?= round($avgGpax, 2) ?>, max: <?= round($maxGpax, 2) ?>, min: <?= round($minGpax, 2) ?> };
            var batchGpaxStats = <?= Json::encode($batchGpaxStats) ?>;
            window.globalAllGpaxStudents = <?= yii\helpers\Json::encode($gpaxStudentsList) ?>;

            window.updateAllStudentCharts = function (mode) {
                // 1. Update Trend Chart
                if (mode === 'total') {
                    window.gpaxChart.data.labels = totalGpaxTrend.labels;
                    window.gpaxChart.data.datasets[0].data = totalGpaxTrend.data;
                    window.gpaxChart.data.datasets[0].label = 'GPAX เฉลี่ยรวม';
                    window.gpaxChart.data.datasets[0].borderColor = '#6366f1';
                } else {
                    var trend = batchGpaxTrend[mode] || {};
                    window.gpaxChart.data.labels = Object.keys(trend);
                    window.gpaxChart.data.datasets[0].data = Object.values(trend);
                    window.gpaxChart.data.datasets[0].label = 'GPAX รหัส ' + mode;
                    window.gpaxChart.data.datasets[0].borderColor = '#8b5cf6';
                }
                window.gpaxChart.update();

                // 2. Update Grouping Chart & Counters
                var groups = (mode === 'total') ? totalGpaxGroups : (batchGpaxGroups[mode] || { r1: 0, r2: 0, r3: 0, r4: 0 });
                window.gpaxGroupChart.data.datasets[0].data = [groups.r1, groups.r2, groups.r3, groups.r4];
                window.gpaxGroupChart.update();

                document.getElementById('gpaxCountR1').innerText = groups.r1;
                document.getElementById('gpaxCountR2').innerText = groups.r2;
                document.getElementById('gpaxCountR3').innerText = groups.r3;
                document.getElementById('gpaxCountR4').innerText = groups.r4;

                // 3. Update Retention Chart & Counters
                var ret = (mode === 'total') ? totalRetention : (batchRetention[mode] || { active: 0, graduated: 0, inactive: 0, dropped: 0 });
                if (mode !== 'total') {
                    var total = ret.active + ret.graduated + ret.inactive + ret.dropped;
                    ret.rate = total > 0 ? Math.round((ret.active + ret.graduated) / total * 100) : 0;
                }
                window.retentionChart.data.datasets[0].data = [ret.active, ret.graduated, ret.inactive, ret.dropped];
                window.retentionChart.update();

                document.getElementById('retentionCountActive').innerText = ret.active;
                document.getElementById('retentionCountGraduated').innerText = ret.graduated;
                document.getElementById('retentionCountInactive').innerText = ret.inactive;
                document.getElementById('retentionCountDropped').innerText = ret.dropped;
                document.getElementById('retentionRateVal').innerText = ret.rate;

                // 4. Update Batch Chart (Highlight selected)
                window.gpaxBatchChart.data.datasets[0].backgroundColor = batchList.map(function (b) {
                    return (b == mode) ? '#f59e0b' : '#8b5cf6'; // Orange for selected, Purple for others
                });
                window.gpaxBatchChart.update();

                // 5. Update KPI Cards (GPA)
                var gStats = (mode === 'total') ? totalGpaxStats : (batchGpaxStats[mode] || { avg: 0, max: 0, min: 0 });
                document.getElementById('kpiAvgGpax').innerText = gStats.avg.toFixed(2);
                document.getElementById('kpiMaxGpax').innerText = gStats.max.toFixed(2);
                document.getElementById('kpiMinGpax').innerText = gStats.min.toFixed(2);
            };

            // License Doughnut
            new Chart(document.getElementById('licenseChart'), {
                type: 'doughnut',
                data: {
                    labels: ['สอบผ่าน', 'ยังไม่ผ่าน'],
                    datasets: [{ data: [<?= $licensePassed ?>, <?= max(0, $licenseTotal - $licensePassed) ?>], backgroundColor: ['#10b981', '#e5e7eb'], borderWidth: 0 }]
                },
                options: { cutout: '70%', plugins: { legend: { display: false } }, responsive: true }
            });

            // ===== PERSONNEL TAB CHARTS =====

            <?php if (!empty($personnelByDept)): ?>
                window.deptChart = new Chart(document.getElementById('deptChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByDept)) ?>,
                        datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByDept)) ?>, backgroundColor: chartColors, borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByContract)): ?>
                window.contractChart = new Chart(document.getElementById('contractChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByContract)) ?>,
                        datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByContract)) ?>, backgroundColor: ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe'], borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        indexAxis: 'y', 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByQualification)): ?>
                window.qualChart = new Chart(document.getElementById('qualChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByQualification)) ?>,
                        datasets: [{ data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByQualification)) ?>, backgroundColor: ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe'], borderWidth: 0, hoverOffset: 6 }]
                    },
                    options: { 
                        cutout: '60%', 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($certByLevel)): ?>
                window.certChart = new Chart(document.getElementById('certChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $certByLevel)) ?>,
                        datasets: [{ label: 'จำนวน', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $certByLevel)) ?>, backgroundColor: ['#f59e0b', '#f97316', '#ef4444', '#ec4899', '#8b5cf6'], borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($topExpertises)): ?>
                window.expertiseChart = new Chart(document.getElementById('expertiseChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $topExpertises)) ?>,
                        datasets: [{ label: 'จำนวนบุคลากร', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $topExpertises)) ?>, backgroundColor: chartColors, borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        indexAxis: 'y', 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByTrack)): ?>
                window.trackChart = new Chart(document.getElementById('trackChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByTrack)) ?>,
                        datasets: [{ data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByTrack)) ?>, backgroundColor: ['#f59e0b', '#3b82f6'], borderWidth: 0, hoverOffset: 6 }]
                    },
                    options: { 
                        cutout: '60%', 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByAcademicPosition)): ?>
                window.academicPosChart = new Chart(document.getElementById('academicPosChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByAcademicPosition)) ?>,
                        datasets: [{ 
                            data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByAcademicPosition)) ?>, 
                            backgroundColor: ['#ec4899', '#f43f5e', '#f472b6', '#fb7185', '#fda4af', '#fecdd3'], 
                            borderWidth: 0, 
                            hoverOffset: 6 
                        }]
                    },
                    options: { 
                        cutout: '60%', 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        } 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($personnelByJobPosition)): ?>
                window.jobPosChart = new Chart(document.getElementById('jobPosChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $personnelByJobPosition)) ?>,
                        datasets: [{ label: 'จำนวนบุคลากร', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $personnelByJobPosition)) ?>, backgroundColor: ['#10b981', '#34d399', '#059669', '#6ee7b7'], borderRadius: 8, borderSkipped: false }]
                    },
                    options: { 
                        indexAxis: 'y', 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            // Data for dynamic updates (Personnel)
            var personnelStatsByTrack = <?= Json::encode($personnelStatsByTrack) ?>;

            window.updateAllPersonnelCharts = function (trackKey) {
                var data = personnelStatsByTrack[trackKey] || {};

                // 1. Dept Chart
                if (window.deptChart) {
                    window.deptChart.data.labels = data.dept.map(r => r.label || 'ไม่ระบุ');
                    window.deptChart.data.datasets[0].data = data.dept.map(r => parseInt(r.total));
                    window.deptChart.update();
                }

                // 2. Contract Chart
                if (window.contractChart) {
                    window.contractChart.data.labels = data.contract.map(r => r.label || 'ไม่ระบุ');
                    window.contractChart.data.datasets[0].data = data.contract.map(r => parseInt(r.total));
                    window.contractChart.update();
                }

                // 3. Qual Chart
                if (window.qualChart) {
                    window.qualChart.data.labels = data.qualification.map(r => r.label || 'ไม่ระบุ');
                    window.qualChart.data.datasets[0].data = data.qualification.map(r => parseInt(r.total));
                    window.qualChart.update();
                }

                // 4. Cert Chart
                if (window.certChart) {
                    window.certChart.data.labels = data.cert.map(r => r.label || 'ไม่ระบุ');
                    window.certChart.data.datasets[0].data = data.cert.map(r => parseInt(r.total));
                    window.certChart.update();
                }

                // 5. Academic Position Chart
                if (window.academicPosChart) {
                    const labels = data.academicPosition.map(r => r.label || 'ไม่ระบุ');
                    const values = data.academicPosition.map(r => parseInt(r.total));
                    const total = values.reduce((a, b) => a + b, 0);
                    
                    window.academicPosChart.data.labels = labels;
                    window.academicPosChart.data.datasets[0].data = values;
                    window.academicPosChart.update();

                    // Update Legend
                    const legendContainer = document.getElementById('academicPosLegend');
                    if (legendContainer) {
                        const colors = ['#ec4899', '#f43f5e', '#f472b6', '#fb7185', '#fda4af', '#fecdd3'];
                        let html = '';
                        data.academicPosition.forEach((row, i) => {
                            const color = colors[i % colors.length];
                            const pct = total > 0 ? ((row.total / total) * 100).toFixed(1) : 0;
                            html += `
                                <div class="flex items-center justify-between text-sm">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background:${color}"></span>
                                        <span class="text-gray-700 truncate">${row.label || 'ไม่ระบุ'}</span>
                                    </span>
                                    <div class="text-right">
                                        <span class="font-semibold text-gray-900">${row.total}</span>
                                        <span class="text-xs text-gray-400 ml-1">(${pct}%)</span>
                                    </div>
                                </div>`;
                        });
                        legendContainer.innerHTML = html;
                    }
                }

                // 6. Job Position Chart
                if (window.jobPosChart) {
                    window.jobPosChart.data.labels = data.jobPosition.map(r => r.label || 'ไม่ระบุ');
                    window.jobPosChart.data.datasets[0].data = data.jobPosition.map(r => parseInt(r.total));
                    window.jobPosChart.update();
                }

                // 7. Expertise Chart
                if (window.expertiseChart) {
                    window.expertiseChart.data.labels = data.expertise.map(r => r.label || 'ไม่ระบุ');
                    window.expertiseChart.data.datasets[0].data = data.expertise.map(r => parseInt(r.total));
                    window.expertiseChart.update();
                }
            };

            // ===== WORK TAB CHARTS =====

            <?php if (!empty($researchByStatus)): ?>
                new Chart(document.getElementById('researchStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $researchByStatus)) ?>,
                        datasets: [{
                            data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $researchByStatus)) ?>,
                            backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                            borderWidth: 0, hoverOffset: 6,
                        }]
                    },
                    options: { 
                        cutout: '60%', 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            <?php if (!empty($academicServiceByStatus)): ?>
                new Chart(document.getElementById('academicServiceStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $academicServiceByStatus)) ?>,
                        datasets: [{
                            data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $academicServiceByStatus)) ?>,
                            backgroundColor: ['#059669', '#34d399', '#6ee7b7', '#a7f3d0', '#ecfdf5'],
                            borderWidth: 0, hoverOffset: 6,
                        }]
                    },
                    options: { 
                        cutout: '60%', 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        responsive: true 
                    }
                });
            <?php endif; ?>

            // Academic Yearly Stats Charts
            <?php if (!empty($academicServiceStats)): ?>
                // Academic Yearly Project Chart
                new Chart(document.getElementById('academicYearlyProjectChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($academicServiceStats, 'label')) ?>,
                        datasets: [{
                            label: 'จำนวนโครงการ',
                            data: <?= Json::encode(array_column($academicServiceStats, 'total_projects')) ?>,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Academic Yearly Budget Chart
                new Chart(document.getElementById('academicYearlyBudgetChart'), {
                    type: 'line',
                    data: {
                        labels: <?= Json::encode(array_column($academicServiceStats, 'label')) ?>,
                        datasets: [{
                            label: 'งบประมาณรวม',
                            data: <?= Json::encode(array_column($academicServiceStats, 'total_budget')) ?>,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#059669',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return 'งบประมาณ: ' + context.parsed.y.toLocaleString() + ' บาท';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            <?php if (!empty($researchByFunding)): ?>
                new Chart(document.getElementById('researchFundingChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_map(fn($r) => $r['label'] ?: 'ไม่ระบุ', $researchByFunding)) ?>,
                        datasets: [{ label: 'จำนวนโครงการ', data: <?= Json::encode(array_map(fn($r) => (int) $r['total'], $researchByFunding)) ?>, backgroundColor: '#6366f1', borderRadius: 8 }]
                    },
                    options: { 
                        responsive: true, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }, 
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
                    }
                });
            <?php endif; ?>

            // Research Yearly Stats Charts
            <?php if (!empty($researchStats)): ?>
                // Research Yearly Project Chart
                new Chart(document.getElementById('researchYearlyProjectChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($researchStats, 'label')) ?>,
                        datasets: [{
                            label: 'จำนวนโครงการ',
                            data: <?= Json::encode(array_column($researchStats, 'total_projects')) ?>,
                            backgroundColor: '#6366f1', // Indigo color
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Research Yearly Budget Chart
                new Chart(document.getElementById('researchYearlyBudgetChart'), {
                    type: 'line',
                    data: {
                        labels: <?= Json::encode(array_column($researchStats, 'label')) ?>,
                        datasets: [{
                            label: 'งบประมาณรวม',
                            data: <?= Json::encode(array_column($researchStats, 'total_budget')) ?>,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return 'งบประมาณ: ' + context.parsed.y.toLocaleString() + ' บาท';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // Innovation Yearly Stats Charts
            <?php if (!empty($innovationStats)): ?>
                // Innovation Yearly Project Chart
                new Chart(document.getElementById('innovationYearlyChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($innovationStats, 'label')) ?>,
                        datasets: [{
                            label: 'จำนวนนวัตกรรม',
                            data: <?= Json::encode(array_column($innovationStats, 'total_projects')) ?>,
                            backgroundColor: '#a855f7', // Purple color
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // Innovation Advisor Chart
            <?php if (!empty($topInnovationAdvisors)): ?>
                new Chart(document.getElementById('innovationAdvisorChart'), {
                    type: 'bar', // Horizontal bar chart is better for long names
                    data: {
                        labels: <?= Json::encode(array_column($topInnovationAdvisors, 'label')) ?>,
                        datasets: [{
                            label: 'ผลงานที่เป็นที่ปรึกษา',
                            data: <?= Json::encode(array_column($topInnovationAdvisors, 'total')) ?>,
                            backgroundColor: ['#c084fc', '#a855f7', '#9333ea', '#7e22ce', '#6b21a8'],
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Horizontal bars
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.x || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                            y: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // Personnel 5-Year Retention Chart
            <?php if (!empty($personnelRetentionStats)): ?>
                new Chart(document.getElementById('personnelRetentionChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_keys($personnelRetentionStats)) ?>,
                        datasets: [{
                            label: 'บุคลากรที่คงอยู่ (คน)',
                            data: <?= Json::encode(array_values($personnelRetentionStats)) ?>,
                            backgroundColor: '#10b981', // Emerald
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        let val = context.parsed.y;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return 'จำนวน: ' + val + ' คน (' + pct + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>
            
            // Scholarship 5-Year Retention Chart
            <?php if (!empty($scholarshipRetentionStats)): ?>
                window.scholarshipRetentionChart = new Chart(document.getElementById('scholarshipRetentionChart'), {
                    type: 'line',
                    data: {
                        labels: <?= Json::encode(array_keys($scholarshipRetentionStats)) ?>,
                        datasets: [{
                            label: 'จำนวนนักเรียนทุน (คน)',
                            data: <?= Json::encode(array_values($scholarshipRetentionStats)) ?>,
                            borderColor: '#f59e0b', // Amber
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#f59e0b',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        let val = context.parsed.y;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return 'จำนวน: ' + val + ' คน (' + pct + '%)';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>

            // ===== MAPS INITIALIZATION =====

            // Research Map
            var researchLocations = <?= Json::encode($researchLocations) ?>;
            if (researchLocations.length > 0) {
                window.researchMap = L.map('researchMap').setView([13.7367, 100.5231], 6); // Default to Thailand center
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(window.researchMap);

                var researchGroup = L.featureGroup();
                researchLocations.forEach(function (loc) {
                    if (loc.latitude && loc.longitude) {
                        // Add slight jitter if multiple projects in same spot
                        var lat = parseFloat(loc.latitude);
                        var lng = parseFloat(loc.longitude);
                        var jitterLat = lat + (Math.random() - 0.5) * 0.001; 
                        var jitterLng = lng + (Math.random() - 0.5) * 0.001;
                        
                        var marker = L.marker([jitterLat, jitterLng])
                            .bindPopup('<b>' + loc.title + '</b>')
                            .addTo(researchGroup);
                    }
                });
                researchGroup.addTo(window.researchMap);
                if (researchGroup.getBounds().isValid()) {
                    window.researchMap.fitBounds(researchGroup.getBounds(), { padding: [50, 50], maxZoom: 12 });
                }
            }

            // Academic Service Map
            var academicLocations = <?= Json::encode($academicServiceLocations) ?>;
            if (academicLocations.length > 0) {
                window.academicServiceMap = L.map('academicServiceMap').setView([13.7367, 100.5231], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(window.academicServiceMap);

                var academicGroup = L.featureGroup();
                academicLocations.forEach(function (loc) {
                    if (loc.latitude && loc.longitude) {
                        // Add slight jitter if multiple projects in same spot
                        var lat = parseFloat(loc.latitude);
                        var lng = parseFloat(loc.longitude);
                        var jitterLat = lat + (Math.random() - 0.5) * 0.001; 
                        var jitterLng = lng + (Math.random() - 0.5) * 0.001;

                        var marker = L.marker([jitterLat, jitterLng])
                            .bindPopup('<b>' + loc.activity_name + '</b>')
                            .addTo(academicGroup);
                    }
                });
                academicGroup.addTo(window.academicServiceMap);
                if (academicGroup.getBounds().isValid()) {
                    window.academicServiceMap.fitBounds(academicGroup.getBounds(), { padding: [50, 50], maxZoom: 12 });
                }
            }

            // ===== BUDGET TAB CHARTS =====
            <?php if (!empty($budgetStats)): ?>
                new Chart(document.getElementById('budgetComparisonChart'), {
                    type: 'bar',
                    data: {
                        labels: <?= Json::encode(array_column($budgetStats, 'label')) ?>,
                        datasets: [
                            {
                                label: 'งบประมาณสุทธิ',
                                data: <?= Json::encode(array_column($budgetStats, 'total_budget')) ?>,
                                backgroundColor: '#6366f1',
                                borderRadius: 6,
                            },
                            {
                                label: 'เบิกจ่ายจริง',
                                data: <?= Json::encode(array_column($budgetStats, 'total_spent')) ?>,
                                backgroundColor: '#f43f5e',
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let val = context.parsed.y;
                                        let otherVal = context.datasetIndex === 0 ? context.chart.data.datasets[1].data[context.dataIndex] : context.chart.data.datasets[0].data[context.dataIndex];
                                        let label = context.dataset.label + ': ' + val.toLocaleString() + ' บาท';
                                        
                                        if (context.datasetIndex === 1) { // เบิกจ่ายจริง
                                            let budget = context.chart.data.datasets[0].data[context.dataIndex];
                                            let pct = budget > 0 ? ((val / budget) * 100).toFixed(2) : 0;
                                            label += ' (' + pct + '% ของงบประมาณ)';
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            <?php endif; ?>
        });
    </script>
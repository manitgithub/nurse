<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'บันทึก GPAX แบบกลุ่ม';
?>

<div x-data="batchGrades()" class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <?= Html::a('←', ['index'], ['class' => 'text-gray-400 hover:text-gray-600 text-2xl']) ?>
        <h1 class="text-2xl font-bold text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">รุ่น (Batch)</label>
                <select x-model="selectedBatch"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">-- เลือกรุ่น --</option>
                    <?php
                    $batches = \app\models\Student::find()->select('batch')->distinct()->column();
                    foreach ($batches as $b): ?>
                        <option value="<?= $b ?>">
                            <?= $b ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">ภาคเรียน / ปีการศึกษา</label>
                <input type="text" x-model="academicYear" placeholder="เช่น 1 / 2568"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 transition">
            </div>
            <div>
                <button @click="fetchStudents" :disabled="!selectedBatch || !academicYear || loading"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">🔍 ดึงข้อมูลนักศึกษา</span>
                    <span x-show="loading">⏳ กำลังโหลด...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div x-show="students.length > 0"
        class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200 transition-all duration-300">
        <form action="<?= Url::to(['batch-create']) ?>" method="post">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
            <input type="hidden" name="academic_year" :value="academicYear">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">นักศึกษา</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase w-32">GPAX
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="(student, index) in students" :key="student.student_id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900" x-text="student.fullname"></span>
                                        <span class="text-xs text-gray-500" x-text="student.student_id"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" step="0.01" min="0" max="4"
                                        :name="'Grades[' + student.student_id + '][gpax]'" x-model="student.gpax"
                                        class="w-full px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 text-center">
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 italic">
                                    <span x-show="student.gpax !== '' && student.gpax !== null">พร้อมบันทึก</span>
                                    <span x-show="student.gpax === '' || student.gpax === null">ว่าง</span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <span class="text-sm text-gray-500">พบนักศึกษา <span class="font-bold text-gray-900"
                        x-text="students.length"></span> คน</span>
                <div class="space-x-2">
                    <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-6 py-2 text-gray-600 hover:text-gray-800 transition']) ?>
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-8 rounded-lg shadow shadow-emerald-200 transition">
                        ✅ บันทึกข้อมูลทั้งหมด
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && students.length === 0 && selectedBatch"
        class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
        <div class="text-4xl mb-4">📭</div>
        <p class="text-gray-500">ไม่พบข้อมูลนักศึกษาในรุ่นที่เลือก</p>
    </div>
</div>

<script>
    function batchGrades() {
        return {
            selectedBatch: '',
            academicYear: '1 / <?= date('Y') + 543 ?>',
            students: [],
            loading: false,
            async fetchStudents() {
                if (!this.selectedBatch || !this.academicYear) return;
                this.loading = true;
                try {
                    const baseUrl = '<?= Url::to(['get-students']) ?>';
                    const separator = baseUrl.includes('?') ? '&' : '?';
                    const response = await fetch(`${baseUrl}${separator}batch=${this.selectedBatch}&year=${encodeURIComponent(this.academicYear)}`);
                    this.students = await response.json();
                } catch (e) {
                    console.error(e);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                }
                this.loading = false;
            }
        }
    }
</script>
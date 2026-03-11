<?php
use yii\helpers\Html;
use yii\helpers\Json;

$this->title = 'บันทึกผลสอบ — แบบกลุ่ม';
$inputClass = 'w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-2 py-1.5 border text-sm';
$statusList = \app\models\ExamResult::getStatusList();
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">📝
        <?= Html::encode($this->title) ?>
    </h1>
    <p class="text-sm text-gray-500 mt-1">เลือกรุ่นและรอบสอบ แล้วกรอกคะแนนทีละหลายคน บันทึกทีเดียว</p>
</div>

<!-- Step 1: Select Batch & Round -->
<div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200 mb-6" x-data="batchExam()" x-init="init()">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">เลือกรุ่น <span
                    class="text-red-500">*</span></label>
            <select x-model="selectedBatch" @change="loadStudents()" class="<?= $inputClass ?>">
                <option value="">-- เลือกรุ่น --</option>
                <?php foreach ($batches as $key => $label): ?>
                    <option value="<?= Html::encode($key) ?>">
                        <?= Html::encode($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">รอบสอบ <span
                    class="text-red-500">*</span></label>
            <select x-model="selectedRound" class="<?= $inputClass ?>">
                <option value="">-- เลือกรอบสอบ --</option>
                <?php foreach ($rounds as $key => $label): ?>
                    <option value="<?= $key ?>">
                        <?= Html::encode($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex items-end">
            <button @click="loadStudents()" :disabled="!selectedBatch"
                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition font-medium text-sm">
                🔍 โหลดรายชื่อ
            </button>
        </div>
    </div>

    <!-- Loading -->
    <div x-show="loading" class="text-center py-8">
        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <p class="text-sm text-gray-500 mt-2">กำลังโหลดรายชื่อ...</p>
    </div>

    <!-- Student Score Table -->
    <template x-if="students.length > 0 && !loading">
        <form method="POST" action="<?= \yii\helpers\Url::to(['batch-create']) ?>">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" :value="csrfToken">
            <input type="hidden" name="round_id" :value="selectedRound">

            <div class="mb-4 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    พบนักศึกษา <span class="font-bold text-indigo-600" x-text="students.length"></span> คน
                    ในรุ่น <span class="font-bold" x-text="selectedBatch"></span>
                </p>
                <button type="submit" :disabled="!selectedRound"
                    class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition font-medium shadow-sm">
                    💾 บันทึกทั้งหมด
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase sticky left-0 bg-gray-50">
                                #</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase sticky left-8 bg-gray-50 min-w-[120px]">
                                รหัส</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase sticky left-32 bg-gray-50 min-w-[160px]">
                                ชื่อ-นามสกุล</th>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <th
                                    class="px-2 py-2 text-center text-xs font-semibold text-gray-500 uppercase min-w-[80px]">
                                    วิชา
                                    <?= $i ?>
                                </th>
                            <?php endfor; ?>
                            <th
                                class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase min-w-[100px]">
                                สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(s, idx) in students" :key="s.student_id">
                            <tr class="hover:bg-indigo-50/30 transition">
                                <td class="px-3 py-2 text-xs text-gray-400 sticky left-0 bg-white" x-text="idx + 1">
                                </td>
                                <td class="px-3 py-2 text-sm font-medium text-indigo-600 sticky left-8 bg-white"
                                    x-text="s.student_id"></td>
                                <td class="px-3 py-2 text-sm text-gray-900 sticky left-32 bg-white" x-text="s.fullname">
                                </td>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <td class="px-1 py-1.5">
                                        <input type="number" step="0.01" min="0"
                                            :name="'ExamResult[' + s.student_id + '][subject_<?= $i ?>_score]'"
                                            class="w-full rounded border-gray-300 px-2 py-1 text-sm border focus:border-indigo-500 focus:ring-indigo-500 text-center"
                                            placeholder="-">
                                    </td>
                                <?php endfor; ?>
                                <td class="px-1 py-1.5">
                                    <select :name="'ExamResult[' + s.student_id + '][status]'"
                                        class="w-full rounded border-gray-300 px-1 py-1 text-sm border focus:border-indigo-500">
                                        <option value="">--</option>
                                        <?php foreach ($statusList as $k => $v): ?>
                                            <option value="<?= $k ?>">
                                                <?= $v ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end space-x-3">
                <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
                <button type="submit" :disabled="!selectedRound"
                    class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition font-medium shadow-sm">
                    💾 บันทึกทั้งหมด
                </button>
            </div>
        </form>
    </template>

    <!-- Empty state -->
    <template x-if="students.length === 0 && !loading && attempted">
        <div class="text-center py-8 text-gray-400">
            <p class="text-lg">ไม่พบนักศึกษาในรุ่นที่เลือก</p>
            <p class="text-sm mt-1">กรุณาตรวจสอบว่ามีการเพิ่มนักศึกษาในรุ่นนี้แล้ว</p>
        </div>
    </template>
</div>

<!-- Alpine.js for tabs is loaded in layout, but ensure it's also here -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function batchExam() {
        return {
            selectedBatch: '',
            selectedRound: '',
            students: [],
            loading: false,
            attempted: false,
            csrfToken: '<?= Yii::$app->request->csrfToken ?>',
            init() { },
            async loadStudents() {
                if (!this.selectedBatch) return;
                this.loading = true;
                this.attempted = true;
                try {
                    const res = await fetch('<?= \yii\helpers\Url::to(['get-students']) ?>&batch=' + encodeURIComponent(this.selectedBatch));
                    this.students = await res.json();
                } catch (e) {
                    this.students = [];
                    console.error(e);
                }
                this.loading = false;
            }
        }
    }
</script>
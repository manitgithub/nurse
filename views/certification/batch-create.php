<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $levels */

$this->title = 'บันทึก UKPSF แบบกลุ่ม';
?>

<div x-data="batchCertify()" class="max-w-full mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">🏅
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <!-- Bulk Settings Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">ตั้งค่าแบบเร็ว (Quick Apply)</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ระดับใบรับรอง</label>
                <select x-model="bulk.level"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="">-- เลือก --</option>
                    <?php foreach ($levels as $id => $name): ?>
                        <option value="<?= $id ?>">
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">วันที่ได้รับ</label>
                <input type="text" x-model="bulk.date"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 text-sm datepicker-be">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">รหัสที่อบรม</label>
                <input type="text" x-model="bulk.batch" placeholder="เช่น รหัส 1"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <button @click="applyBulk"
                    class="w-full bg-gray-800 hover:bg-black text-white py-2 px-4 rounded text-sm font-bold transition">
                    Apply to Selected
                </button>
            </div>
        </div>
    </div>

    <!-- Personnel Table -->
    <div class="bg-white shadow-xl rounded-xl border border-gray-200 overflow-hidden">
        <form method="post">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

            <div class="overflow-x-auto min-h-[400px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" @change="toggleAll"
                                    class="rounded text-indigo-600 focus:ring-white">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">บุคลากร / สาขา
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest w-48">ระดับ
                                UKPSF</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest w-40">
                                วันที่ได้รับ</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest w-32">
                                รหัสที่อบรม</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="p in personnel" :key="p.id">
                            <tr class="hover:bg-indigo-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" x-model="selected" :value="p.id"
                                        class="rounded text-indigo-600">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900" x-text="p.fullname"></span>
                                        <span class="text-xs text-gray-400" x-text="p.department_name"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <select :name="'Certifications[' + p.id + '][certification_level_id]'"
                                        x-model="p.certification_level_id"
                                        class="w-full px-2 py-1.5 border border-gray-200 rounded text-sm focus:border-indigo-500">
                                        <option value="">-- ยังไม่มี --</option>
                                        <?php foreach ($levels as $id => $name): ?>
                                            <option value="<?= $id ?>">
                                                <?= $name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" :name="'Certifications[' + p.id + '][certified_date]'"
                                        x-model="p.certified_date"
                                        class="w-full px-2 py-1 border border-gray-200 rounded text-sm focus:border-indigo-500 datepicker-be">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" :name="'Certifications[' + p.id + '][training_batch]'"
                                        x-model="p.training_batch"
                                        class="w-full px-2 py-1 border border-gray-200 rounded text-sm focus:border-indigo-500">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <span class="text-sm text-gray-500">พบบุคลากร <span class="font-bold text-indigo-600"
                        x-text="personnel.length"></span> ลำดับ</span>
                <div class="flex space-x-3">
                    <?= Html::a('ยกเลิก', ['/personnel/index'], ['class' => 'px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition']) ?>
                    <button type="submit"
                        class="px-10 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg hover:shadow-indigo-200 transition transform active:scale-95">
                        📦 บันทึกข้อมูลใบรับรองทั้งหมด
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function batchCertify() {
        return {
            personnel: [],
            selected: [],
            bulk: { level: '', date: '', batch: '' },
            loading: true,
            async init() {
                try {
                    const res = await fetch('<?= Url::to(['ajax-get-personnel']) ?>');
                    this.personnel = await res.json();
                } catch (e) { console.error(e); }
                this.loading = false;
            },
            toggleAll(e) {
                if (e.target.checked) {
                    this.selected = this.personnel.map(p => p.id);
                } else {
                    this.selected = [];
                }
            },
            applyBulk() {
                if (this.selected.length === 0) {
                    alert('กรุณาเลือกบุคลากรที่ต้องการปรับปรุงข้อมูล');
                    return;
                }
                this.personnel.forEach(p => {
                    if (this.selected.includes(p.id)) {
                        if (this.bulk.level) p.certification_level_id = this.bulk.level;
                        if (this.bulk.date) p.certified_date = this.bulk.date;
                        if (this.bulk.batch) p.training_batch = this.bulk.batch;
                    }
                });
            }
        }
    }
</script>
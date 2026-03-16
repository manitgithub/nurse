<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'นำเข้าข้อมูล GPAX จาก Excel';
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">📊
        <?= Html::encode($this->title) ?>
    </h1>
    <p class="text-sm text-gray-500 mt-1">อัปโหลดไฟล์ (.xlsx, .xls, .csv) เพื่อนำเข้าข้อมูลเกรดเฉลี่ยสะสมทีละหลายคน</p>
</div>

<div class="max-w-full mx-auto space-y-6">
    <!-- Format Instructions -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-amber-800">
        <h3 class="font-semibold flex items-center mb-2">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            รูปแบบไฟล์ที่รองรับ
        </h3>
        <p class="text-sm mb-3">กรุณาจัดเรียงคอลัมน์ในไฟล์ Excel ดังนี้ (เริ่มบรรทัดที่ 2):</p>
        <div class="overflow-hidden rounded-lg border border-amber-200 bg-white">
            <table class="min-w-full divide-y divide-amber-100">
                <thead class="bg-amber-100/50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">คอลัมน์ A</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">คอลัมน์ B</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">คอลัมน์ C</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">คอลัมน์ D</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100">
                    <tr class="text-xs">
                        <td class="px-4 py-2">เทอม (เช่น 1)</td>
                        <td class="px-4 py-2">ปีการศึกษา (เช่น 2568)</td>
                        <td class="px-4 py-2">รหัสนักศึกษา</td>
                        <td class="px-4 py-2">GPAX (เช่น 3.50)</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs mt-3 opacity-80">* ระบบจะรวม "เทอม" และ "ปี" เป็น "1/2568" อัตโนมัติ</p>
    </div>

    <!-- Upload Form -->
    <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-200 mt-6">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'space-y-6'],
        ]); ?>

        <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-2xl p-10 hover:border-indigo-400 transition cursor-pointer"
            onclick="document.getElementById('excel-input').click()">
            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-sm font-medium text-gray-700">คลิกเพื่อเลือกไฟล์ หรือลากไฟล์มาวางที่นี่</p>
            <p class="text-xs text-gray-400 mt-1">.xlsx, .xls หรือ .csv</p>
            <input type="file" name="excelFile" id="excel-input" class="hidden" accept=".xlsx,.xls,.csv"
                onchange="updateFileName(this)">
            <p id="file-name" class="mt-4 text-sm text-indigo-600 font-semibold hidden"></p>
        </div>

        <div class="flex justify-between items-center pt-6 border-t">
            <?= Html::a('← ยกเลิก', ['index'], ['class' => 'px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition']) ?>
            <button type="submit"
                class="px-8 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition font-bold">
                🚀 เริ่มนำเข้าข้อมูล
            </button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileNameDisplay = document.getElementById('file-name');
        if (input.files && input.files.length > 0) {
            fileNameDisplay.textContent = 'ไฟล์ที่เลือก: ' + input.files[0].name;
            fileNameDisplay.classList.remove('hidden');
        } else {
            fileNameDisplay.classList.add('hidden');
        }
    }
</script>
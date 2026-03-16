<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'รายละเอียดการเบิกจ่าย: ' . $allocation->category->name;

$thaiDate = function($date) {
    if (!$date) return '-';
    $t = strtotime($date);
    $thai_months = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค. "];
    return date('j', $t) . ' ' . $thai_months[date('n', $t)] . ' ' . (date('Y', $t) + 543);
};
?>

<div class="budget-transactions bg-gray-50 min-h-screen p-6">
    <div class="max-w-full mx-auto">
        <div class="mb-6 flex items-center justify-between">
             <?= Html::a('← กลับไปหน้าสรุป', ['index', 'fiscal_year' => $allocation->fiscal_year], ['class' => 'text-blue-600 hover:underline font-medium']) ?>
             <a href="<?= Url::to(['budget/create-transaction', 'allocation_id' => $allocation->id]) ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-lg transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                บันทึกรายจ่าย
            </a>
        </div>

        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 p-8 rounded-2xl shadow-xl mb-8 text-white">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold"><?= Html::encode($this->title) ?></h1>
                    <p class="opacity-80 text-lg">ปีงบประมาณ <?= $allocation->fiscal_year ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <p class="text-xs opacity-70 uppercase tracking-widest font-bold mb-1">งบประมาณจัดสรร</p>
                    <p class="text-3xl font-black"><?= number_format($allocation->getTotalBudget(), 2) ?></p>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <p class="text-xs opacity-70 uppercase tracking-widest font-bold mb-1">ค่าใช้จ่าย ณ ปัจจุบัน</p>
                    <p class="text-3xl font-black text-yellow-300"><?= number_format($allocation->getTotalExpenses(), 2) ?></p>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <p class="text-xs opacity-70 uppercase tracking-widest font-bold mb-1">งบประมาณคงเหลือ ณ ปัจจุบัน</p>
                    <p class="text-3xl font-black text-green-300"><?= number_format($allocation->getRemainingBalance(), 2) ?></p>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <p class="text-xs opacity-70 uppercase tracking-widest font-bold mb-1">ร้อยละค่าใช้จ่าย</p>
                    <?php 
                        $spentPercent = $allocation->getTotalBudget() > 0 ? ($allocation->getTotalExpenses() / $allocation->getTotalBudget()) * 100 : 0;
                    ?>
                    <p class="text-3xl font-black text-purple-200"><?= number_format($spentPercent, 2) ?>%</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">รายการเบิกจ่าย (Expenses)</h2>
        </div> <!-- id: end_header -->

        <div class="overflow-x-auto relative shadow-xl sm:rounded-2xl border border-gray-100 bg-white">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="py-4 px-4 font-bold border-b">วดป.</th>
                        <th class="py-4 px-4 font-bold border-b">กิจกรรม</th>
                        <th class="py-4 px-4 font-bold border-b">รายวิชา</th>
                        <th class="py-4 px-4 font-bold border-b">ผู้เบิก</th>
                        <th class="py-4 px-4 text-right font-bold border-b bg-yellow-50">เสนอขออนุมัติ</th>
                        <th class="py-4 px-4 text-right font-bold border-b">ค่าตอบแทน/ค่าจ้าง</th>
                        <th class="py-4 px-4 text-right font-bold border-b">ค่าที่พัก</th>
                        <th class="py-4 px-4 text-right font-bold border-b">ค่าวัสดุ/ใช้สอยอื่น</th>
                        <th class="py-4 px-4 text-right font-bold border-b">ค่ารับรอง/ค่าเบี้ยเลี้ยง</th>
                        <th class="py-4 px-4 text-right font-bold border-b">ค่าพาหนะ</th>
                        <th class="py-4 px-4 text-right font-bold border-b bg-gray-800 text-white">รวมค่าใช้จ่าย</th>
                        <th class="py-4 px-4 text-right font-bold border-b bg-blue-50 text-blue-700">คงเหลือ</th>
                        <th class="py-4 px-4 text-right font-bold border-b bg-green-50 text-green-700">คงเหลือสุทธิ</th>
                        <th class="py-4 px-4 font-bold border-b">อ้างอิง/หมายเหตุ</th>
                        <th class="py-4 px-4 font-bold border-b text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $netRemaining = $allocation->getTotalBudget();
                    foreach ($transactions as $t): 
                        $totalCost = $t->getTotalCost();
                        $balance = $t->proposed_amount - $totalCost;
                        $netRemaining -= $totalCost;
                    ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-4"><?= $thaiDate($t->transaction_date) ?></td>
                        <td class="py-4 px-4 font-medium text-gray-900"><?= Html::encode($t->activity_name) ?></td>
                        <td class="py-4 px-4 text-indigo-600 font-semibold"><?= Html::encode($t->subject_name) ?></td>
                        <td class="py-4 px-4"><?= Html::encode($t->requester) ?></td>
                        <td class="py-4 px-4 text-right font-bold bg-yellow-50 text-gray-900"><?= number_format((float)$t->proposed_amount, 2) ?></td>
                        <td class="py-4 px-4 text-right"><?= number_format((float)$t->cost_compensation, 2) ?></td>
                        <td class="py-4 px-4 text-right"><?= number_format((float)$t->cost_accommodation, 2) ?></td>
                        <td class="py-4 px-4 text-right"><?= number_format((float)$t->cost_materials, 2) ?></td>
                        <td class="py-4 px-4 text-right"><?= number_format((float)$t->cost_hospitality, 2) ?></td>
                        <td class="py-4 px-4 text-right"><?= number_format((float)$t->cost_transportation, 2) ?></td>
                        <td class="py-4 px-4 text-right font-bold bg-gray-800 text-white"><?= number_format($totalCost, 2) ?></td>
                        <td class="py-4 px-4 text-right font-bold bg-blue-50 text-blue-700"><?= number_format($balance, 2) ?></td>
                        <td class="py-4 px-4 text-right font-bold bg-green-50 text-green-700"><?= number_format($netRemaining, 2) ?></td>
                        <td class="py-4 px-4">
                            <span class="block text-xs text-blue-600 font-bold"><?= Html::encode($t->reference_no) ?></span>
                            <span class="block text-xs text-gray-400 italic"><?= Html::encode($t->note) ?></span>
                        </td>
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?= Url::to(['budget/update-transaction', 'id' => $t->id]) ?>" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded-lg transition-colors" title="แก้ไข">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                <a href="<?= Url::to(['budget/delete-transaction', 'id' => $t->id]) ?>" 
                                   class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-lg transition-colors" 
                                   title="ลบ"
                                   onclick="return confirm('ยืนยันการลบรายการนี้?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-800 text-white font-bold">
                    <tr>
                        <td colspan="12" class="py-4 px-4 text-right border-r border-gray-700">เงินคงเหลือสุทธิ</td>
                        <td class="py-4 px-4 text-right text-lg border-r border-gray-700"><?= number_format($allocation->getRemainingBalance(), 2) ?></td>
                        <td colspan="2" class="bg-gray-900 px-4 italic text-sm text-gray-500 font-normal">หักล้างจากงบประมาณจัดสรรรวม</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

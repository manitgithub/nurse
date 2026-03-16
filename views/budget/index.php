<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'สรุปรายรับ-รายจ่าย ประจำปีงบประมาณ ' . $fiscal_year;
?>

<div class="budget-index bg-gray-50 min-h-screen p-6">
    <div class="max-w-full mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 border-l-4 border-blue-600 pl-4"><?= Html::encode($this->title) ?></h1>
            <div class="flex gap-4">
                <form method="get" class="flex items-center gap-2">
                    <input type="hidden" name="r" value="budget/index">
                    <select name="fiscal_year" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <?php 
                        $currentYear = date('Y') + 543;
                        for($y = $currentYear + 1; $y >= $currentYear - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= $y == $fiscal_year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto relative shadow-2xl sm:rounded-2xl border border-gray-100 bg-white">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="py-4 px-6 font-bold">หมวดกิจกรรม</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-blue-700">จำนวนเงินได้รับจัดสรร</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-red-600">ปรับลดระหว่างปี</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-green-600">รับเพิ่มระหว่างปี</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-gray-900 border-x">รวมงบประมาณ</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-orange-600">ค่าใช้จ่ายระหว่างปี</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-blue-600 border-x">คงเหลือ</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-indigo-700">ร้อยละค่าใช้จ่าย</th>
                        <th scope="col" class="py-4 px-6 text-right font-bold text-teal-600">ร้อยละคงเหลือ</th>
                        <th scope="col" class="py-4 px-6 text-center font-bold">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalAllocated = 0;
                    $totalReduced = 0;
                    $totalAdded = 0;
                    $totalBudget = 0;
                    $totalSpent = 0;
                    $totalRemaining = 0;

                    foreach ($categories as $cat): 
                        $alloc = $allocations[$cat->id] ?? null;
                        
                        $allocated = $alloc ? (float)$alloc->allocated_amount : 0;
                        $reduced = $alloc ? (float)$alloc->adjustment_reduction : 0;
                        $added = $alloc ? (float)$alloc->adjustment_addition : 0;
                        $budget = $allocated - $reduced + $added;
                        $spent = $alloc ? (float)$alloc->getTotalExpenses() : 0;
                        $remaining = $budget - $spent;
                        
                        $spentPercent = $budget > 0 ? ($spent / $budget) * 100 : 0;
                        $remainPercent = $budget > 0 ? ($remaining / $budget) * 100 : 0;

                        $totalAllocated += $allocated;
                        $totalReduced += $reduced;
                        $totalAdded += $added;
                        $totalBudget += $budget;
                        $totalSpent += $spent;
                        $totalRemaining += $remaining;
                    ?>
                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 font-medium text-gray-900 leading-tight w-1/4">
                            <?= Html::encode($cat->name) ?>
                        </td>
                        <td class="py-4 px-6 text-right"><?= number_format($allocated, 2) ?></td>
                        <td class="py-4 px-6 text-right text-red-500"><?= number_format($reduced, 2) ?></td>
                        <td class="py-4 px-6 text-right text-green-500"><?= number_format($added, 2) ?></td>
                        <td class="py-4 px-6 text-right text-gray-900 font-bold border-x bg-gray-50"><?= number_format($budget, 2) ?></td>
                        <td class="py-4 px-6 text-right text-orange-600">
                            <?php if ($alloc): ?>
                                <a href="<?= Url::to(['budget/transactions', 'allocation_id' => $alloc->id]) ?>" class="hover:underline">
                                    <?= number_format($spent, 2) ?>
                                </a>
                            <?php else: ?>
                                0.00
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-6 text-right text-blue-700 font-bold border-x bg-gray-50"><?= number_format($remaining, 2) ?></td>
                        <td class="py-4 px-6 text-right">
                             <span class="px-2 py-1 rounded bg-indigo-50 text-indigo-700 font-medium"><?= number_format($spentPercent, 2) ?>%</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                             <span class="px-2 py-1 rounded bg-teal-50 text-teal-700 font-medium"><?= number_format($remainPercent, 2) ?>%</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="<?= Url::to(['budget/allocation', 'id' => $alloc ? $alloc->id : null, 'category_id' => $cat->id]) ?>" class="text-blue-600 hover:text-blue-900 transition-colors bg-blue-50 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>จัดสรร</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-blue-600 text-white font-bold">
                    <tr>
                        <td class="py-4 px-6 text-lg">รวมงบประมาณดำเนินการปีงบ <?= $fiscal_year ?></td>
                        <td class="py-4 px-6 text-right"><?= number_format($totalAllocated, 2) ?></td>
                        <td class="py-4 px-6 text-right"><?= number_format($totalReduced, 2) ?></td>
                        <td class="py-4 px-6 text-right"><?= number_format($totalAdded, 2) ?></td>
                        <td class="py-4 px-6 text-right border-x bg-blue-700"><?= number_format($totalBudget, 2) ?></td>
                        <td class="py-4 px-6 text-right"><?= number_format($totalSpent, 2) ?></td>
                        <td class="py-4 px-6 text-right border-x bg-blue-700"><?= number_format($totalRemaining, 2) ?></td>
                        <?php 
                        $totalSpentPercent = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;
                        $totalRemainPercent = $totalBudget > 0 ? ($totalRemaining / $totalBudget) * 100 : 0;
                        ?>
                        <td class="py-4 px-6 text-right"><?= number_format($totalSpentPercent, 2) ?>%</td>
                        <td class="py-4 px-6 text-right"><?= number_format($totalRemainPercent, 2) ?>%</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

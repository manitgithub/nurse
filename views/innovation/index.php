<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'นวัตกรรม';
?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">💡
        <?= Html::encode($this->title) ?>
    </h1>
    <?= Html::a('+ เพิ่มนวัตกรรม', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-sm transition-all']) ?>
</div>

<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        ชื่อนวัตกรรม</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ว/ด/ป
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        อาจารย์ที่ปรึกษา</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">จัดการ
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($dataProvider->getModels() as $model): ?>
                    <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                <?= Html::encode($model->name) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $model->invention_date ? date('d/m/Y', strtotime($model->invention_date)) : '-' ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= Html::encode($model->advisor) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <?= Html::a('ดู', ['view', 'id' => $model->id], ['class' => 'text-indigo-600 hover:text-indigo-900']) ?>
                            <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'text-amber-600 hover:text-amber-900']) ?>
                            <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
                                'class' => 'text-red-600 hover:text-red-900',
                                'data' => [
                                    'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($dataProvider->getCount() === 0): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">ยังไม่มีข้อมูลนวัตกรรม</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'flex justify-center space-x-1'],
            'linkContainerOptions' => ['class' => 'inline-block'],
            'linkOptions' => ['class' => 'px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-100'],
            'disabledPageCssClass' => 'opacity-50 cursor-not-allowed',
            'activePageCssClass' => 'bg-indigo-600 text-white border-indigo-600',
        ]) ?>
    </div>
</div>
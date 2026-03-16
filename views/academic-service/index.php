<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'บริการวิชาการ/ทำนุบำรุงวัฒนธรรม';
?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">📋 <?= Html::encode($this->title) ?></h1>
    <?= Html::a('+ เพิ่มกิจกรรม', ['create'], ['class' => 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition']) ?>
</div>

<!-- ==================== Summary Stats Cards ==================== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100 shadow-sm">
        <h3 class="text-sm font-semibold text-indigo-800 mb-1">จำนวนโครงการบริการวิชาการรวม</h3>
        <p class="text-3xl font-bold text-indigo-900"><?= number_format($totalActivities) ?></p>
        <p class="text-xs text-indigo-600 mt-1">อ้างอิงตามตัวกรองปัจจุบัน</p>
    </div>

    <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100 shadow-sm">
        <h3 class="text-sm font-semibold text-emerald-800 mb-1">งบประมาณรวม</h3>
        <p class="text-3xl font-bold text-emerald-900">฿<?= number_format($totalBudget, 2) ?></p>
        <p class="text-xs text-emerald-600 mt-1">อ้างอิงตามตัวกรองปัจจุบัน</p>
    </div>

    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-semibold text-gray-800 mb-2">สถานะโครงการ (ตามตัวกรอง)</h3>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($statusBreakdown as $sb): ?>
                <?php
                $bgClass = 'bg-gray-200 text-gray-800';
                if ($sb['status'] === 'ดำเนินการเสร็จสิ้นแล้ว')
                    $bgClass = 'bg-green-100 text-green-800';
                elseif ($sb['status'] === 'กำลังดำเนินการ')
                    $bgClass = 'bg-blue-100 text-blue-800';
                ?>
                <div class="flex items-center space-x-2 px-3 py-1 rounded-full <?= $bgClass ?> text-sm font-medium">
                    <span><?= Html::encode($sb['status'] ?: 'ไม่ระบุ') ?></span>
                    <span class="bg-white/50 px-2 py-0.5 rounded-full text-xs"><?= $sb['count'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (empty($statusBreakdown)): ?>
                <span class="text-gray-400 text-sm">ไม่มีข้อมูล</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ==================== Data Grid ==================== -->
<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
        'headerRowOptions' => ['class' => 'bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider'],
        'filterRowOptions' => ['class' => 'bg-gray-50 border-b border-gray-200'],
        'rowOptions' => ['class' => 'hover:bg-gray-50 transition border-b border-gray-100 text-sm text-gray-700'],
        'layout' => "{items}\n<div class='flex items-center justify-between px-6 py-3 bg-gray-50 border-t border-gray-200'>{summary}\n{pager}</div>",
        'summary' => "<div class='text-sm text-gray-500'>แสดง <b>{begin}-{end}</b> จาก <b>{totalCount}</b> รายการ</div>",
        'pager' => [
            'class' => \yii\widgets\LinkPager::class,
            'options' => ['class' => 'flex space-x-1 justify-center'],
            'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white border border-gray-300 text-sm hover:bg-indigo-50 hover:text-indigo-600'],
            'activePageCssClass' => 'bg-indigo-50 border-indigo-500 text-indigo-600 font-bold',
            'disabledPageCssClass' => 'opacity-50 cursor-not-allowed',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['class' => 'px-6 py-3 w-12'], 'contentOptions' => ['class' => 'px-6 py-4 text-gray-400']],

            [
                'attribute' => 'activity_name',
                'headerOptions' => ['class' => 'px-6 py-3'],
                'contentOptions' => ['class' => 'px-6 py-4 font-medium text-gray-900 max-w-xs truncate'],
                'filterInputOptions' => ['class' => 'w-full px-2 py-1 rounded border border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500'],
            ],
            [
                'attribute' => 'fiscal_year',
                'headerOptions' => ['class' => 'px-6 py-3 w-32'],
                'contentOptions' => ['class' => 'px-6 py-4 text-gray-500 text-center'],
                'filterInputOptions' => ['class' => 'w-full px-2 py-1 rounded border border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-center', 'prompt' => 'ทั้งหมด'],
            ],
            [
                'attribute' => 'budget_amount',
                'format' => ['decimal', 2],
                'headerOptions' => ['class' => 'px-6 py-3 text-right', 'style' => 'width: 120px;'],
                'contentOptions' => ['class' => 'px-6 py-4 text-emerald-600 font-semibold text-right'],
                'filterInputOptions' => ['class' => 'w-full px-2 py-1 rounded border border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-right'],
            ],
            [
                'label' => 'ระยะเวลา',
                'value' => function ($model) {
        return $model->start_date . ($model->end_date ? ' - ' . $model->end_date : '');
    },
                'headerOptions' => ['class' => 'px-6 py-3 w-48'],
                'contentOptions' => ['class' => 'px-6 py-4 text-gray-500 text-xs'],
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'headerOptions' => ['class' => 'px-6 py-3 w-40'],
                'contentOptions' => ['class' => 'px-6 py-4'],
                'filter' => [
                    'กำลังดำเนินการ' => 'กำลังดำเนินการ',
                    'ดำเนินการเสร็จสิ้นแล้ว' => 'ดำเนินการเสร็จสิ้นแล้ว',
                ],
                'filterInputOptions' => ['class' => 'w-full px-2 py-1 rounded border border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500', 'prompt' => 'ทุกสถานะ'],
                'value' => function ($model) {
        $statusColor = 'bg-gray-100 text-gray-700';
        if ($model->status === 'ดำเนินการเสร็จสิ้นแล้ว')
            $statusColor = 'bg-green-100 text-green-700';
        elseif ($model->status === 'กำลังดำเนินการ')
            $statusColor = 'bg-blue-100 text-blue-700';

        return "<span class='inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$statusColor}'>" . Html::encode($model->status) . "</span>";
    },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'จัดการ',
                'headerOptions' => ['class' => 'px-6 py-3 text-right w-32'],
                'contentOptions' => ['class' => 'px-6 py-4 text-right space-x-2'],
                'buttons' => [
                    'view' => function ($url, $model) {
            return Html::a('ดู', $url, ['class' => 'text-indigo-600 hover:text-indigo-900 font-medium text-sm', 'data-pjax' => '0']);
        },
                    'update' => function ($url, $model) {
            return Html::a('แก้ไข', $url, ['class' => 'text-amber-600 hover:text-amber-900 font-medium text-sm', 'data-pjax' => '0']);
        },
                    'delete' => function ($url, $model) {
            return Html::a('ลบ', $url, [
                'class' => 'text-red-600 hover:text-red-900 font-medium text-sm',
                'data' => [
                    'confirm' => 'คุณต้องการลบข้อมูลนี้หรือไม่?',
                    'method' => 'post',
                    'pjax' => '0'
                ],
            ]);
        },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
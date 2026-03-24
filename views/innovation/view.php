<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Innovation $model */

$this->title = $model->name;
?>
<?php
if (!function_exists('_formatDateSafe')) {
    function _formatDateSafe($dateStr)
    {
        if (empty($dateStr)) return '-';
        $s = trim((string)$dateStr);
        $d = $mo = $y = null;
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $s, $m)) {
            $y = (int)$m[1]; $mo = (int)$m[2]; $d = (int)$m[3];
        } elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $s, $m)) {
            $d = (int)$m[1]; $mo = (int)$m[2]; $y = (int)$m[3];
        } elseif (preg_match('/(19|20|25)\d{2}/', $s, $m)) {
            $y = (int)$m[0]; if ($y > 2400) $y -= 543; $mo = 1; $d = 1;
        } else {
            try {
                $dt = new DateTime($s);
                $y = (int)$dt->format('Y'); $mo = (int)$dt->format('n'); $d = (int)$dt->format('j');
            } catch (Exception $e) {
                return '-';
            }
        }
        $months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];
        return sprintf('%d %s %d', $d, $months[$mo] ?? $mo, $y);
    }
}
?>

<div class="max-w-5xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
            <?= Html::a('←', ['index'], ['class' => 'text-gray-400 hover:text-gray-600 text-2xl transition-colors']) ?>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">รายละเอียดนวัตกรรม</h1>
        </div>
        <div class="space-x-3">
            <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition-all']) ?>
            <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
                'class' => 'bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition-all',
                'data' => [
                    'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-2xl font-black text-gray-900 mb-6">
                    <?= Html::encode($model->name) ?>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-sm">
                    <div class="bg-indigo-50 p-4 rounded-2xl">
                        <span class="block text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-1">ว/ด/ป
                            ที่คิดค้น</span>
                        <span class="text-indigo-900 font-bold">
                            <?= $model->invention_date ? (function($d){ try { return (new \DateTime($d))->format('j F Y'); } catch(\Exception $e) { return '-'; } })($model->invention_date) : '-' ?>
                        </span>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-2xl">
                        <span
                            class="block text-[10px] font-black uppercase text-amber-400 tracking-widest mb-1">อาจารย์ที่ปรึกษา</span>
                        <span class="text-amber-900 font-bold">
                            <?= Html::encode($model->advisor ?: '-') ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <h3 class="flex items-center text-sm font-black text-gray-400 uppercase tracking-widest mb-3">
                            <span class="bg-gray-100 w-8 h-[2px] mr-3"></span> สถานการณ์หรือปัญหา
                        </h3>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line pl-11 border-l-2 border-gray-50">
                            <?= Html::encode($model->problem) ?>
                        </div>
                    </div>

                    <div>
                        <h3 class="flex items-center text-sm font-black text-gray-400 uppercase tracking-widest mb-3">
                            <span class="bg-gray-100 w-8 h-[2px] mr-3"></span> กระบวนการนวัตกรรม
                        </h3>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line pl-11 border-l-2 border-gray-50">
                            <?= Html::encode($model->process) ?>
                        </div>
                    </div>

                    <div>
                        <h3 class="flex items-center text-sm font-black text-gray-400 uppercase tracking-widest mb-3">
                            <span class="bg-gray-100 w-8 h-[2px] mr-3"></span> ผลลัพธ์/การนำไปใช้
                        </h3>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line pl-11 border-l-2 border-gray-50">
                            <?= Html::encode($model->results) ?>
                        </div>
                    </div>

                    <div>
                        <h3 class="flex items-center text-sm font-black text-gray-400 uppercase tracking-widest mb-3">
                            <span class="bg-gray-100 w-8 h-[2px] mr-3"></span> ผู้พัฒนานวัตกรรม
                        </h3>
                        <div class="text-gray-700 leading-bold pl-11 border-l-2 border-gray-50 italic">
                            <?= Html::encode($model->developers) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photos Section -->
            <?php if (count($model->images) > 0): ?>
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                        <span class="bg-emerald-500 w-2 h-6 mr-3 rounded"></span> รายรูปภาพผลงาน
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($model->images as $file): ?>
                            <a href="<?= Url::to('@web/' . $file->file_path) ?>" target="_blank"
                                class="block aspect-square overflow-hidden rounded-2xl shadow-sm hover:shadow-xl transition-all hover:-translate-y-1">
                                <img src="<?= Url::to('@web/' . $file->file_path) ?>" class="w-full h-full object-cover">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Location Map Card -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">พิกัดสถานที่</h3>
                </div>
                <div id="map" class="h-64 w-full bg-gray-50"></div>
                <?php if ($model->latitude && $model->longitude): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var map = L.map('map').setView([<?= $model->latitude ?>, <?= $model->longitude ?>], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                            L.marker([<?= $model->latitude ?>, <?= $model->longitude ?>]).addTo(map);
                        });
                    </script>
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <?php else: ?>
                    <div class="h-64 flex items-center justify-center text-gray-400 italic text-sm">ไม่ระบุพิกัด</div>
                <?php endif; ?>
            </div>

            <!-- Files Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">เอกสารแนบ</h3>
                <div class="space-y-3">
                    <?php foreach ($model->attachments as $file): ?>
                        <a href="<?= Url::to('@web/' . $file->file_path) ?>" target="_blank"
                            class="flex items-center p-3 bg-gray-50 hover:bg-indigo-50 rounded-2xl transition-colors group">
                            <div
                                class="bg-indigo-100 p-2 rounded-xl mr-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-600 truncate group-hover:text-indigo-900">
                                <?= Html::encode($file->original_name) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                    <?php if (count($model->attachments) === 0): ?>
                        <div class="text-center py-4 text-gray-400 text-xs italic">ไม่มีเอกสารแนบ</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
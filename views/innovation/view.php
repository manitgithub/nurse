<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Innovation $model */

$this->title = $model->name;
?>

<!-- Google Fonts Link for premium typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .font-premium {
        font-family: 'Inter', 'Sarabun', 'Kanit', sans-serif;
    }
</style>

<div class="font-premium space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-purple-50 text-purple-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md">นวัตกรรมและสิ่งประดิษฐ์</span>
                <h1 class="text-xl font-bold text-gray-900 mt-1">รายละเอียดนวัตกรรม</h1>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <?= Html::a('← ย้อนกลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm flex items-center']) ?>
            <?= Html::a('✏️ แก้ไขข้อมูล', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-sm font-semibold transition shadow-md hover:shadow-lg flex items-center']) ?>
            <?= Html::a('🗑️ ลบข้อมูล', ['delete', 'id' => $model->id], [
                'class' => 'px-4 py-2 border border-red-200 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium transition shadow-sm flex items-center',
                'data' => [
                    'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <!-- Hero Header Panel -->
    <div class="relative overflow-hidden bg-gradient-to-r from-purple-900 via-slate-900 to-indigo-950 text-white rounded-2xl shadow-xl p-8 md:p-10">
        <!-- Background decorative glows -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-500 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute right-1/4 bottom-0 w-60 h-20 bg-purple-500 rounded-full blur-3xl opacity-20"></div>

        <div class="relative z-10 space-y-6">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1 text-xs font-semibold">
                    💡 นวัตกรรมเด่น
                </span>
                <?php if ($model->invention_date): ?>
                    <span class="inline-flex items-center rounded-lg bg-white/10 text-white/90 border border-white/10 px-3 py-1 text-xs font-semibold">
                        🗓️ วันคิดค้น: 
                        <?= (function($d){ try { return (new \DateTime($d))->format('j F Y'); } catch(\Exception $e) { return '-'; } })($model->invention_date) ?>
                    </span>
                <?php endif; ?>
            </div>

            <h2 class="text-2xl md:text-3xl font-bold leading-snug tracking-tight text-white max-w-4xl">
                <?= Html::encode($model->name) ?>
            </h2>

            <!-- Advisor and Developers list footer -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-white/10 text-sm">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-9 w-9 rounded-xl bg-purple-500/25 border border-purple-500/35 flex items-center justify-center text-sm font-semibold text-purple-300">
                        👨‍🏫
                    </div>
                    <div>
                        <p class="text-white/60 text-xs font-medium">อาจารย์ที่ปรึกษา</p>
                        <p class="font-semibold text-white/90 mt-0.5"><?= Html::encode($model->advisor ?: 'ไม่ระบุ') ?></p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-9 w-9 rounded-xl bg-indigo-500/25 border border-indigo-500/35 flex items-center justify-center text-sm font-semibold text-indigo-300">
                        👥
                    </div>
                    <div>
                        <p class="text-white/60 text-xs font-medium">ผู้พัฒนานวัตกรรม</p>
                        <p class="font-semibold text-white/90 mt-0.5 truncate max-w-sm" title="<?= Html::encode($model->developers) ?>">
                            <?= Html::encode($model->developers ?: 'ไม่ระบุ') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Two-Column Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Main Narrative sections (col-span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Problems and Process Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-8">
                
                <!-- Section: ปัญหาที่มา -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 pb-2 border-b border-gray-50">
                        <span class="text-lg">🚨</span>
                        <h3 class="text-md font-bold text-gray-800">สถานการณ์หรือปัญหาที่ก่อให้เกิดนวัตกรรม</h3>
                    </div>
                    <div class="bg-red-50/20 text-gray-700 rounded-xl p-5 border border-red-100/30 text-sm leading-relaxed whitespace-pre-line font-medium pl-6">
                        <?= Html::encode($model->problem) ?: 'ไม่มีข้อมูลสถานการณ์หรือปัญหาเริ่มต้น' ?>
                    </div>
                </div>

                <!-- Section: กระบวนการพัฒนา -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 pb-2 border-b border-gray-50">
                        <span class="text-lg">⚙️</span>
                        <h3 class="text-md font-bold text-gray-800">กระบวนการพัฒนาหรือทำงานของนวัตกรรม</h3>
                    </div>
                    <div class="bg-indigo-50/20 text-gray-700 rounded-xl p-5 border border-indigo-100/30 text-sm leading-relaxed whitespace-pre-line font-medium pl-6">
                        <?= Html::encode($model->process) ?: 'ไม่มีข้อมูลกระบวนการพัฒนาหรือคู่มือการทำงาน' ?>
                    </div>
                </div>

                <!-- Section: ผลลัพธ์การนำไปใช้ -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 pb-2 border-b border-gray-50">
                        <span class="text-lg">🏆</span>
                        <h3 class="text-md font-bold text-gray-800">ผลลัพธ์และการนำนวัตกรรมไปใช้ประโยชน์</h3>
                    </div>
                    <div class="bg-emerald-50/20 text-gray-700 rounded-xl p-5 border border-emerald-100/30 text-sm leading-relaxed whitespace-pre-line font-medium pl-6">
                        <?= Html::encode($model->results) ?: 'ไม่มีข้อมูลผลการนำไปใช้ประโยชน์ในปัจจุบัน' ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Side: Location Map, Attachments, Images (col-span 1) -->
        <div class="space-y-6">

            <!-- Card 2: Location Map -->
            <?php if ($model->latitude && $model->longitude): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">📍</span>
                            <h3 class="text-sm font-bold text-gray-800">พิกัดสถานที่ติดตั้ง</h3>
                        </div>
                        <a href="https://www.google.com/maps?q=<?= $model->latitude ?>,<?= $model->longitude ?>" target="_blank"
                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center">
                            Google Maps &rarr;
                        </a>
                    </div>

                    <!-- Map container -->
                    <div id="map" class="w-full h-48 rounded-xl border border-gray-200 z-0"></div>

                    <div class="grid grid-cols-2 gap-2 text-center bg-gray-50 py-2.5 rounded-xl border border-gray-100 text-xs">
                        <div>
                            <span class="text-gray-400">ละติจูด:</span>
                            <span class="font-semibold text-gray-700 block mt-0.5"><?= $model->latitude ?></span>
                        </div>
                        <div class="border-l border-gray-200">
                            <span class="text-gray-400">ลองจิจูด:</span>
                            <span class="font-semibold text-gray-700 block mt-0.5"><?= $model->longitude ?></span>
                        </div>
                    </div>
                </div>

                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var map = L.map('map', { zoomControl: false }).setView([<?= $model->latitude ?>, <?= $model->longitude ?>], 14);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        
                        L.control.zoom({ position: 'bottomright' }).addTo(map);
                        
                        L.marker([<?= $model->latitude ?>, <?= $model->longitude ?>]).addTo(map)
                            .bindPopup('<b><?= addslashes(Html::encode($model->name)) ?></b>').openPopup();
                    });
                </script>
            <?php else: ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-2 text-center">
                    <span class="text-gray-300 text-2xl block">📍</span>
                    <span class="text-xs text-gray-400 block">ไม่มีการระบุพิกัดในระบบ</span>
                </div>
            <?php endif; ?>

            <!-- Card 3: Document Attachments -->
            <?php $attachments = $model->attachments; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                    <span class="text-lg">📎</span>
                    <h3 class="text-sm font-bold text-gray-800">เอกสารแนบ (<?= count($attachments) ?>)</h3>
                </div>

                <?php if ($attachments): ?>
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <?php foreach ($attachments as $file): ?>
                            <a href="<?= Url::to('@web/' . $file->file_path) ?>" target="_blank"
                                class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-purple-50 border border-transparent hover:border-purple-100 transition duration-200 group">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-purple-500 mr-3 group-hover:bg-purple-100 group-hover:text-purple-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-purple-600 font-semibold truncate flex-1 group-hover:text-purple-700">
                                    <?= Html::encode($file->original_name) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-400 text-center py-4">ไม่มีเอกสารแนบในระบบ</p>
                <?php endif; ?>
            </div>

            <!-- Card 4: Image Gallery Preview -->
            <?php $images = $model->images; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                    <span class="text-lg">🖼️</span>
                    <h3 class="text-sm font-bold text-gray-800">รูปภาพประกอบ (<?= count($images) ?>)</h3>
                </div>

                <?php if ($images): ?>
                    <div class="grid grid-cols-2 gap-3">
                        <?php foreach ($images as $file): ?>
                            <a href="<?= Url::to('@web/' . $file->file_path) ?>" target="_blank" 
                               class="group block relative overflow-hidden rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                <img src="<?= Url::to('@web/' . $file->file_path) ?>"
                                    alt="<?= Html::encode($file->original_name) ?>"
                                    class="w-full h-24 object-cover transform group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-200 flex items-center justify-center">
                                    <span class="text-xs font-semibold text-white bg-black/60 px-2 py-1 rounded">ขยายรูป</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-400 text-center py-4">ไม่มีรูปภาพประกอบ</p>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>
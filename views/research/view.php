<?php
use yii\helpers\Html;

$this->title = $model->title;
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
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">ข้อมูลงานวิจัย</span>
                <h1 class="text-xl font-bold text-gray-900 mt-1">รายละเอียดโครงการวิจัย</h1>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <?= Html::a('← ย้อนกลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm flex items-center']) ?>
            <?= Html::a('✏️ แก้ไขข้อมูล', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-sm font-semibold transition shadow-md hover:shadow-lg flex items-center']) ?>
        </div>
    </div>

    <!-- Hero Header Panel -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white rounded-2xl shadow-xl p-8 md:p-10">
        <!-- Background decorative glows -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute right-1/4 bottom-0 w-60 h-20 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>

        <div class="relative z-10 space-y-6">
            <div class="flex flex-wrap items-center gap-2">
                <?php
                $statusColor = 'bg-slate-800 text-slate-300';
                if ($model->work_status === 'ตีพิมพ์') {
                    $statusColor = 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                } elseif ($model->work_status === 'อยู่ระหว่างดำเนินการ') {
                    $statusColor = 'bg-blue-500/20 text-blue-300 border border-blue-500/30';
                }
                ?>
                <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-semibold <?= $statusColor ?>">
                    ● <?= Html::encode($model->work_status) ?: 'ไม่ระบุสถานะ' ?>
                </span>
                <?php if ($model->year): ?>
                    <span class="inline-flex items-center rounded-lg bg-white/10 text-white/90 border border-white/10 px-3 py-1 text-xs font-semibold">
                        🗓️ ปี พ.ศ. <?= Html::encode($model->year) ?>
                    </span>
                <?php endif; ?>
                <?php if ($model->publish_level): ?>
                    <span class="inline-flex items-center rounded-lg bg-indigo-500/20 text-indigo-200 border border-indigo-500/30 px-3 py-1 text-xs font-semibold">
                        📢 ระดับ: <?= Html::encode($model->publish_level) ?>
                    </span>
                <?php endif; ?>
            </div>

            <h2 class="text-2xl md:text-3xl font-bold leading-snug tracking-tight text-white max-w-4xl">
                <?= Html::encode($model->title) ?>
            </h2>

            <!-- Authors list footer -->
            <div class="flex items-center space-x-3 pt-4 border-t border-white/10">
                <div class="flex -space-x-1 overflow-hidden">
                    <div class="inline-block h-8 w-8 rounded-full ring-2 ring-slate-800 bg-indigo-500 flex items-center justify-center text-xs font-bold text-white uppercase">
                        <?= mb_substr($model->authors ?: 'U', 0, 1) ?>
                    </div>
                </div>
                <div class="text-sm">
                    <p class="text-white/60 text-xs">คณะผู้ดำเนินงาน / เจ้าของผลงาน</p>
                    <p class="font-medium text-white/90 mt-0.5"><?= Html::encode($model->authors) ?: 'ไม่ระบุผู้ดำเนินงาน' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Two-Column Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Main Project and Financial Details (col-span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Project Metadata -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                    <span class="text-lg">📄</span>
                    <h3 class="text-lg font-bold text-gray-900">รายละเอียดและสถานะโครงการ</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <span class="text-xs font-medium text-gray-400">ระยะเวลาดำเนินการ</span>
                        <div class="flex items-center space-x-2 text-sm text-gray-800 mt-1">
                            <span class="text-gray-400">⏱️</span>
                            <span class="font-medium"><?= Html::encode($model->duration) ?: 'ไม่ระบุ' ?></span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-xs font-medium text-gray-400">ระดับชั้นผลงาน (Tier)</span>
                        <div class="flex items-center space-x-2 text-sm text-gray-800 mt-1">
                            <span class="text-gray-400">🏆</span>
                            <span class="font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded"><?= Html::encode($model->tier) ?: 'ไม่ระบุ' ?></span>
                        </div>
                    </div>
                </div>

                <!-- Narrative results text area -->
                <div class="space-y-2 pt-2">
                    <span class="text-xs font-medium text-gray-400">ผลการดำเนินการ / แหล่งเผยแพร่</span>
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 text-sm text-gray-700 leading-relaxed whitespace-pre-line font-medium">
                        <?= Html::encode($model->result_publication) ?: 'ไม่มีรายละเอียดผลการดำเนินการเพิ่มเติม' ?>
                    </div>
                </div>
            </div>

            <!-- Card 2: Financials and Funding -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                    <span class="text-lg">💰</span>
                    <h3 class="text-lg font-bold text-gray-900">ทุนและงบประมาณ</h3>
                </div>

                <!-- Financial Highlight Box -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gradient-to-br from-slate-50 to-indigo-50/20 rounded-xl p-5 border border-gray-100">
                    <div class="md:col-span-2 space-y-1">
                        <span class="text-xs font-medium text-indigo-600">งบประมาณสนับสนุนโครงการ</span>
                        <div class="text-2xl md:text-3xl font-extrabold text-indigo-900 mt-1">
                            <?= $model->budget ? number_format($model->budget, 2) : '0.00' ?> <span class="text-sm font-semibold text-indigo-600 ml-1">บาท</span>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center border-t md:border-t-0 md:border-l border-gray-200/60 pt-3 md:pt-0 md:pl-5 space-y-1">
                        <span class="text-xs font-medium text-gray-400">สถานะแหล่งทุน</span>
                        <span class="inline-flex self-start items-center rounded-full bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 mt-1">
                            <?= Html::encode($model->funding_status) ?: 'ไม่ระบุ' ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-1">
                    <span class="text-xs font-medium text-gray-400">แหล่งที่มาของทุนวิจัย</span>
                    <div class="text-sm font-semibold text-gray-800 bg-gray-50 px-4 py-3 rounded-lg border border-gray-100 mt-1">
                        <?= Html::encode($model->funding_source) ?: 'ไม่ระบุแหล่งทุน' ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Side: Location Map, Attachments, Images (col-span 1) -->
        <div class="space-y-6">

            <!-- Card 3: Coordinates Map -->
            <?php if ($model->latitude && $model->longitude): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">📍</span>
                            <h3 class="text-sm font-bold text-gray-800">พิกัดโครงการ</h3>
                        </div>
                        <a href="https://www.google.com/maps?q=<?= $model->latitude ?>,<?= $model->longitude ?>" target="_blank"
                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center">
                            Google Maps &rarr;
                        </a>
                    </div>

                    <!-- Map container -->
                    <div id="view-map-research" class="w-full h-48 rounded-xl border border-gray-200 z-0"></div>

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
                        var map = L.map('view-map-research', { zoomControl: false }).setView([<?= $model->latitude ?>, <?= $model->longitude ?>], 14);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        
                        L.control.zoom({ position: 'bottomright' }).addTo(map);
                        
                        L.marker([<?= $model->latitude ?>, <?= $model->longitude ?>]).addTo(map)
                            .bindPopup('<b><?= addslashes(Html::encode($model->title)) ?></b>').openPopup();
                    });
                </script>
            <?php endif; ?>

            <!-- Card 4: Document Attachments -->
            <?php $attachments = $model->attachments; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                    <span class="text-lg">📎</span>
                    <h3 class="text-sm font-bold text-gray-800">เอกสารแนบ (<?= count($attachments) ?>)</h3>
                </div>

                <?php if ($attachments): ?>
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <?php foreach ($attachments as $file): ?>
                            <a href="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>" target="_blank"
                                class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-indigo-50 border border-transparent hover:border-indigo-100 transition duration-200 group">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-indigo-500 mr-3 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-indigo-600 font-semibold truncate flex-1 group-hover:text-indigo-700">
                                    <?= Html::encode($file->original_name) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-400 text-center py-4">ไม่มีเอกสารแนบในระบบ</p>
                <?php endif; ?>
            </div>

            <!-- Card 5: Image Gallery Preview -->
            <?php $images = $model->images; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                    <span class="text-lg">🖼️</span>
                    <h3 class="text-sm font-bold text-gray-800">รูปภาพประกอบ (<?= count($images) ?>)</h3>
                </div>

                <?php if ($images): ?>
                    <div class="grid grid-cols-2 gap-3">
                        <?php foreach ($images as $file): ?>
                            <a href="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>" target="_blank" 
                               class="group block relative overflow-hidden rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                <img src="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>"
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
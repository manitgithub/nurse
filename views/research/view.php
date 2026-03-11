<?php
use yii\helpers\Html;
$this->title = $model->title;
?>
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900 max-w-2xl">🔬
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2 flex-shrink-0">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>

<div class="max-w-4xl space-y-6">
    <!-- ข้อมูลผลงาน -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📄 ข้อมูลผลงาน</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">ชื่อผลงาน</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->title) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">พ.ศ.</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->year) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">สถานะผลงาน</dt>
                <dd class="mt-1">
                    <?php
                    $statusColor = 'bg-gray-100 text-gray-700';
                    if ($model->work_status === 'ตีพิมพ์') {
                        $statusColor = 'bg-green-100 text-green-700';
                    } elseif ($model->work_status === 'อยู่ระหว่างดำเนินการ') {
                        $statusColor = 'bg-blue-100 text-blue-700';
                    }
                    ?>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $statusColor ?>">
                        <?= Html::encode($model->work_status) ?: '-' ?>
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ชื่อเจ้าของผลงาน</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->authors) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ระยะเวลาดำเนินการ</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->duration) ?: '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- แหล่งทุน -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">💰 แหล่งทุน</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">สถานะแหล่งทุน</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->funding_status) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">งบประมาณ (บาท)</dt>
                <dd class="text-sm text-gray-900 mt-1 font-semibold">
                    <?= $model->budget ? number_format($model->budget, 2) : '-' ?>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">ชื่อแหล่งทุน</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->funding_source) ?: '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- การเผยแพร่ -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📢 การเผยแพร่</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">เผยแพร่ระดับ</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->publish_level) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ระดับชั้น</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->tier) ?: '-' ?>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">ผลการดำเนินการ/แหล่งเผยแพร่</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->result_publication) ?: '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- พิกัดตำแหน่ง -->
    <?php if ($model->latitude && $model->longitude): ?>
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📍 พิกัดตำแหน่ง</h2>
            <div id="view-map-research" class="w-full h-80 rounded-lg border border-gray-300 mb-4 z-0"></div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">ละติจูด</dt>
                    <dd class="text-sm text-gray-900 mt-1"><?= $model->latitude ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">ลองจิจูด</dt>
                    <dd class="text-sm text-gray-900 mt-1"><?= $model->longitude ?></dd>
                </div>
            </dl>
            <div class="mt-3">
                <a href="https://www.google.com/maps?q=<?= $model->latitude ?>,<?= $model->longitude ?>" target="_blank"
                    class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                    🗺️ ดูบน Google Maps →
                </a>
            </div>
        </div>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var map = L.map('view-map-research').setView([<?= $model->latitude ?>, <?= $model->longitude ?>], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);
                L.marker([<?= $model->latitude ?>, <?= $model->longitude ?>]).addTo(map)
                    .bindPopup('<?= addslashes(Html::encode($model->title)) ?>').openPopup();
            });
        </script>
    <?php endif; ?>

    <!-- ไฟล์แนบ -->
    <?php $attachments = $model->attachments; ?>
    <?php if ($attachments): ?>
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📎 ไฟล์แนบ (
                <?= count($attachments) ?>)
            </h2>
            <div class="space-y-2">
                <?php foreach ($attachments as $file): ?>
                    <a href="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>" target="_blank"
                        class="flex items-center px-4 py-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="text-sm text-indigo-600 font-medium">
                            <?= Html::encode($file->original_name) ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- ภาพประกอบ -->
    <?php $images = $model->images; ?>
    <?php if ($images): ?>
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">🖼️ ภาพประกอบ (
                <?= count($images) ?>)
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($images as $file): ?>
                    <a href="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>" target="_blank" class="block">
                        <img src="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>"
                            alt="<?= Html::encode($file->original_name) ?>"
                            class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:shadow-md transition">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
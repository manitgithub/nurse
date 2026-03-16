<?php
use yii\helpers\Html;
$this->title = $model->activity_name;
?>
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">📋
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="space-x-2">
        <?= Html::a('✏️ แก้ไข', ['update', 'id' => $model->id], ['class' => 'px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-medium shadow-sm']) ?>
        <?= Html::a('← กลับ', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
    </div>
</div>

<div class="max-w-4xl space-y-6">
    <!-- ข้อมูลหลัก -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📌 ข้อมูลหลัก</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">กิจกรรม</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->activity_name) ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ประจำปีงบประมาณ</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->fiscal_year) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ลักษณะโครงการ</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->project_type) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ประเด็นมุ่งเน้นของโครงการ</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->project_focus) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
                <dd class="mt-1">
                    <?php
                    $statusColor = 'bg-gray-100 text-gray-700';
                    if ($model->status === 'ดำเนินการเสร็จสิ้นแล้ว') {
                        $statusColor = 'bg-green-100 text-green-700';
                    } elseif ($model->status === 'กำลังดำเนินการ') {
                        $statusColor = 'bg-blue-100 text-blue-700';
                    }
                    ?>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $statusColor ?>">
                        <?= Html::encode($model->status) ?: '-' ?>
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    <!-- ยุทธศาสตร์ (Strategic Info) -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">🎯 ยุทธศาสตร์ มวล. และ QA</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">เป้าหมายผลสัมฤทธิ์เชิงยุทธศาสตร์</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->strategic_goal) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ประเด็นมุ่งเน้นของโครงการ</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->project_focus) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500"><?= $model->getAttributeLabel('strategic_number') ?></dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->strategic_number) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">เป้าประสงค์</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->strategic_objective) ?: '-' ?>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500 mb-2">ตัวบ่งชี้ประกันคุณภาพ</dt>
                <dd class="text-sm text-gray-900 mt-1 space-y-1">
                    <?php if ($model->qa_indicator_1) echo '<div>- ' . Html::encode($model->qa_indicator_1) . '</div>'; ?>
                    <?php if ($model->qa_indicator_2) echo '<div>- ' . Html::encode($model->qa_indicator_2) . '</div>'; ?>
                    <?php if ($model->qa_indicator_3) echo '<div>- ' . Html::encode($model->qa_indicator_3) . '</div>'; ?>
                    <?php if ($model->qa_indicator_4) echo '<div>- ' . Html::encode($model->qa_indicator_4) . '</div>'; ?>
                    <?php if ($model->qa_indicator_5) echo '<div>- ' . Html::encode($model->qa_indicator_5) . '</div>'; ?>
                    <?php if (!$model->qa_indicator_1 && !$model->qa_indicator_2 && !$model->qa_indicator_3 && !$model->qa_indicator_4 && !$model->qa_indicator_5) echo '-'; ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- บูรณาการ (Integration) -->
    <div class="bg-gray-50 shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">🔗 การบูรณาการ</h2>
        <dl class="space-y-4">
            
            <?php if ($model->integration_teaching): ?>
            <div class="bg-white p-3 rounded shadow-sm border border-gray-100">
                <dt class="text-sm font-medium text-indigo-700">✓ บูรณาการการเรียนการสอน</dt>
                <dd class="text-sm text-gray-700 mt-1 ml-5">
                    รายวิชา: <strong><?= Html::encode($model->integration_teaching_subject) ?: '-' ?></strong> 
                    ภาคการศึกษาที่: <strong><?= Html::encode($model->integration_teaching_semester) ?: '-' ?></strong>
                </dd>
            </div>
            <?php endif; ?>

            <?php if ($model->integration_student_activity): ?>
            <div class="bg-white p-3 rounded shadow-sm border border-gray-100">
                <dt class="text-sm font-medium text-indigo-700">✓ บูรณาการกับกิจกรรมนักศึกษา</dt>
                <dd class="text-sm text-gray-700 mt-1 ml-5">
                    ขยายความ/ชื่อกิจกรรม: <strong><?= Html::encode($model->integration_student_activity_desc) ?: '-' ?></strong>
                </dd>
            </div>
            <?php endif; ?>

            <?php if ($model->integration_academic_service): ?>
            <div class="bg-white p-3 rounded shadow-sm border border-gray-100">
                <dt class="text-sm font-medium text-indigo-700">✓ บูรณาการกับงานบริการวิชาการ</dt>
                <dd class="text-sm text-gray-700 mt-1 ml-5">
                    ขยายความ/ชื่องาน: <strong><?= Html::encode($model->integration_academic_service_desc) ?: '-' ?></strong>
                </dd>
            </div>
            <?php endif; ?>

            <?php if ($model->integration_research): ?>
            <div class="bg-white p-3 rounded shadow-sm border border-gray-100">
                <dt class="text-sm font-medium text-indigo-700">✓ บูรณาการงานวิจัย</dt>
                <dd class="text-sm text-gray-700 mt-1 ml-5">
                    งานวิจัย: <strong><?= Html::encode($model->integration_research_desc) ?: '-' ?></strong>
                </dd>
            </div>
            <?php endif; ?>
            
            <?php if ($model->integration_other): ?>
            <div class="bg-white p-3 rounded shadow-sm border border-gray-100">
                <dt class="text-sm font-medium text-indigo-700">✓ บูรณาการอื่นๆ</dt>
                <dd class="text-sm text-gray-700 mt-1 ml-5">
                    รายละเอียด: <strong><?= Html::encode($model->integration_other) ?></strong>
                </dd>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 pt-4 border-t border-gray-200">
                <div>
                    <dt class="text-sm font-medium text-gray-500">ปัญหาอุปสรรค(หลังจากบูรณาการ)</dt>
                    <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                        <?= Html::encode($model->integration_problems) ?: '-' ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">แนวทางการแก้ไข(หลังจากบูรณาการ)</dt>
                    <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                        <?= Html::encode($model->integration_solutions) ?: '-' ?>
                    </dd>
                </div>
            </div>
        </dl>
    </div>

    <!-- งบประมาณ -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">💰 งบประมาณ</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500"><?= $model->getAttributeLabel('budget_source') ?></dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->budget_source) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500"><?= $model->getAttributeLabel('budget_amount') ?></dt>
                <dd class="text-sm text-gray-900 mt-1 font-semibold">
                    <?= $model->budget_amount ? number_format($model->budget_amount, 2) : '-' ?>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500"><?= $model->getAttributeLabel('budget_category') ?></dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->budget_category) ?: '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- ระยะเวลาและผู้รับผิดชอบ -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📅 ระยะเวลาและผู้รับผิดชอบ</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">วันที่เริ่ม</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= $model->start_date ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ถึงวันที่</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= $model->end_date ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ผู้รับผิดชอบโครงการ</dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= Html::encode($model->responsible_person) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">บทบาทของอาจารย์</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->teacher_role) ?: '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- ผลการดำเนินงาน -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📊 ผลการดำเนินงาน</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">กลุ่มผู้รับบริการ / พื้นที่จัดกิจกรรม</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->target_group) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500"><?= $model->getAttributeLabel('participants_count') ?></dt>
                <dd class="text-sm text-gray-900 mt-1">
                    <?= $model->participants_count ? number_format($model->participants_count) : '-' ?>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">ผลการดำเนินงาน สรุปผลเป็น %</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->result_percentage) ?: '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- ปัญหาและแนวทาง -->
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">⚠️ ปัญหาและแนวทางแก้ไข</h2>
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">ปัญหาอุปสรรค</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->problems) ?: '-' ?>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">แนวทางการแก้ไข</dt>
                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                    <?= Html::encode($model->solutions) ?: '-' ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- พิกัดตำแหน่ง -->
    <?php if ($model->latitude && $model->longitude): ?>
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📍 พิกัดตำแหน่ง</h2>
            <div id="view-map-academic" class="w-full h-80 rounded-lg border border-gray-300 mb-4 z-0"></div>
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
                var map = L.map('view-map-academic').setView([<?= $model->latitude ?>, <?= $model->longitude ?>], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);
                L.marker([<?= $model->latitude ?>, <?= $model->longitude ?>]).addTo(map)
                    .bindPopup('<?= addslashes(Html::encode($model->activity_name)) ?>').openPopup();
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
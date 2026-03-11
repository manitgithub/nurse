<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border';
$textareaClass = $inputClass . ' min-h-[80px]';
?>
<div class="max-w-4xl mx-auto bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-5', 'enctype' => 'multipart/form-data']]); ?>

    <!-- ข้อมูลหลัก -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📌 ข้อมูลหลัก</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">กิจกรรม <span
                        class="text-red-500">*</span></label>
                <?= $form->field($model, 'activity_name')->textInput(['class' => $inputClass, 'placeholder' => 'เช่น วันพยาบาลแห่งชาติ รำลึกสมเด็จย่า 2558'])->label(false) ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประจำปีงบประมาณ</label>
                    <?= $form->field($model, 'fiscal_year')->textInput(['class' => $inputClass, 'placeholder' => 'เช่น 2559'])->label(false) ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ลักษณะโครงการ</label>
                    <?= $form->field($model, 'project_type')->dropDownList([
                        'วิชาชีพ' => 'วิชาชีพ',
                        'วิชาการ' => 'วิชาการ',
                        'บริการสังคม' => 'บริการสังคม',
                        'อื่นๆ' => 'อื่นๆ',
                    ], ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ยุทธศาสตร์ -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">🎯 ยุทธศาสตร์</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เป้าหมายผลสัมฤทธิ์เชิงยุทธศาสตร์</label>
                <?= $form->field($model, 'strategic_goal')->textarea(['class' => $textareaClass, 'rows' => 3])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ความเชื่อมโยง/ตอบสนองยุทธศาสตร์</label>
                <?= $form->field($model, 'strategy_link')->textarea(['class' => $textareaClass, 'rows' => 3])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ประเด็นมุ่งเน้นของโครงการ</label>
                <?= $form->field($model, 'project_focus')->textInput(['class' => $inputClass])->label(false) ?>
            </div>
        </div>
    </div>

    <!-- งบประมาณ -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">💰 งบประมาณ</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">แหล่งงบประมาณ</label>
                <?= $form->field($model, 'budget_source')->dropDownList([
                    'ภายใน' => 'ภายใน',
                    'ภายนอก' => 'ภายนอก',
                ], ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ค่าใช้จ่ายในโครงการ (บาท)</label>
                <?= $form->field($model, 'budget_amount')->textInput(['class' => $inputClass, 'type' => 'number', 'step' => '0.01', 'placeholder' => '0.00'])->label(false) ?>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">หมวดงบประมาณที่ใช้</label>
            <?= $form->field($model, 'budget_category')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
    </div>

    <!-- ระยะเวลาและผู้รับผิดชอบ -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📅 ระยะเวลาและผู้รับผิดชอบ</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เริ่ม</label>
                <?= $form->field($model, 'start_date')->textInput(['class' => $inputClass, 'type' => 'date'])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                <?= $form->field($model, 'end_date')->textInput(['class' => $inputClass, 'type' => 'date'])->label(false) ?>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ผู้รับผิดชอบโครงการ</label>
                <?= $form->field($model, 'responsible_person')->textInput(['class' => $inputClass])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">บทบาทของอาจารย์</label>
                <?= $form->field($model, 'teacher_role')->textInput(['class' => $inputClass])->label(false) ?>
            </div>
        </div>
    </div>

    <!-- ผลการดำเนินงาน -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📊 ผลการดำเนินงาน</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">กลุ่มผู้รับบริการ /
                    พื้นที่จัดกิจกรรม</label>
                <?= $form->field($model, 'target_group')->textarea(['class' => $textareaClass, 'rows' => 2])->label(false) ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนผู้เข้ารับบริการ (คน)</label>
                    <?= $form->field($model, 'participants_count')->textInput(['class' => $inputClass, 'type' => 'number'])->label(false) ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                    <?= $form->field($model, 'status')->dropDownList([
                        'กำลังดำเนินการ' => 'กำลังดำเนินการ',
                        'ดำเนินการเสร็จสิ้นแล้ว' => 'ดำเนินการเสร็จสิ้นแล้ว',
                        'ยกเลิก' => 'ยกเลิก',
                    ], ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ผลการดำเนินงาน สรุปผลเป็น %</label>
                <?= $form->field($model, 'result_percentage')->textarea(['class' => $textareaClass, 'rows' => 2])->label(false) ?>
            </div>
        </div>
    </div>

    <!-- ปัญหาและแนวทาง -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">⚠️ ปัญหาและแนวทางแก้ไข</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ปัญหาอุปสรรค</label>
                <?= $form->field($model, 'problems')->textarea(['class' => $textareaClass, 'rows' => 3])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">แนวทางการแก้ไข</label>
                <?= $form->field($model, 'solutions')->textarea(['class' => $textareaClass, 'rows' => 3])->label(false) ?>
            </div>
        </div>
    </div>

    <!-- พิกัดตำแหน่ง -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📍 พิกัดตำแหน่ง</h2>
        <p class="text-sm text-gray-500 mb-3">คลิกบนแผนที่เพื่อปักหมุดตำแหน่ง หรือกรอกพิกัดด้านล่าง</p>
        <div id="map-academic" class="w-full h-80 rounded-lg border border-gray-300 mb-4 z-0"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ละติจูด</label>
                <?= $form->field($model, 'latitude')->textInput(['class' => $inputClass, 'type' => 'number', 'step' => '0.0000001', 'placeholder' => 'เช่น 13.7563309', 'id' => 'lat-input'])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ลองจิจูด</label>
                <?= $form->field($model, 'longitude')->textInput(['class' => $inputClass, 'type' => 'number', 'step' => '0.0000001', 'placeholder' => 'เช่น 100.5017651', 'id' => 'lng-input'])->label(false) ?>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var defaultLat = <?= $model->latitude ?: '8.6455' ?>;
            var defaultLng = <?= $model->longitude ?: '99.8966' ?>;
            var hasCoords = <?= ($model->latitude && $model->longitude) ? 'true' : 'false' ?>;
            var zoom = hasCoords ? 15 : 10;

            var map = L.map('map-academic').setView([defaultLat, defaultLng], zoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            var marker = null;
            if (hasCoords) {
                marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
                marker.on('dragend', function (e) {
                    var pos = e.target.getLatLng();
                    document.getElementById('lat-input').value = pos.lat.toFixed(7);
                    document.getElementById('lng-input').value = pos.lng.toFixed(7);
                });
            }

            map.on('click', function (e) {
                var lat = e.latlng.lat.toFixed(7);
                var lng = e.latlng.lng.toFixed(7);
                document.getElementById('lat-input').value = lat;
                document.getElementById('lng-input').value = lng;
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng, { draggable: true }).addTo(map);
                    marker.on('dragend', function (ev) {
                        var pos = ev.target.getLatLng();
                        document.getElementById('lat-input').value = pos.lat.toFixed(7);
                        document.getElementById('lng-input').value = pos.lng.toFixed(7);
                    });
                }
            });

            // Sync from input fields
            ['lat-input', 'lng-input'].forEach(function (id) {
                document.getElementById(id).addEventListener('change', function () {
                    var lat = parseFloat(document.getElementById('lat-input').value);
                    var lng = parseFloat(document.getElementById('lng-input').value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        var latlng = L.latLng(lat, lng);
                        map.setView(latlng, 15);
                        if (marker) {
                            marker.setLatLng(latlng);
                        } else {
                            marker = L.marker(latlng, { draggable: true }).addTo(map);
                            marker.on('dragend', function (ev) {
                                var pos = ev.target.getLatLng();
                                document.getElementById('lat-input').value = pos.lat.toFixed(7);
                                document.getElementById('lng-input').value = pos.lng.toFixed(7);
                            });
                        }
                    }
                });
            });
        });
    </script>

    <!-- ไฟล์แนบ -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📎 ไฟล์แนบ</h2>
        <?php if (!$model->isNewRecord && $model->attachments): ?>
            <div class="mb-3 space-y-1">
                <p class="text-sm text-gray-500 mb-2">ไฟล์แนบปัจจุบัน:</p>
                <?php foreach ($model->attachments as $file): ?>
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
                        <a href="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>" target="_blank"
                            class="text-sm text-indigo-600 hover:text-indigo-800">
                            <?= Html::encode($file->original_name) ?>
                        </a>
                        <?= Html::a('✕', ['delete-file', 'id' => $model->id, 'fileId' => $file->id], ['class' => 'text-red-500 hover:text-red-700 text-sm', 'data' => ['confirm' => 'ลบไฟล์นี้?', 'method' => 'post']]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <input type="file" name="attachments[]" multiple
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        <p class="mt-1 text-xs text-gray-400">สามารถเลือกได้หลายไฟล์</p>
    </div>

    <!-- ภาพประกอบ -->
    <div class="pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">🖼️ ภาพประกอบ</h2>
        <?php if (!$model->isNewRecord && $model->images): ?>
            <div class="mb-3 grid grid-cols-2 md:grid-cols-4 gap-3">
                <?php foreach ($model->images as $file): ?>
                    <div class="relative group">
                        <img src="<?= Yii::getAlias('@web') . '/' . $file->file_path ?>"
                            alt="<?= Html::encode($file->original_name) ?>" class="w-full h-32 object-cover rounded-lg border">
                        <?= Html::a('✕', ['delete-file', 'id' => $model->id, 'fileId' => $file->id], ['class' => 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition', 'data' => ['confirm' => 'ลบภาพนี้?', 'method' => 'post']]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <input type="file" name="images[]" multiple accept="image/*"
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
        <p class="mt-1 text-xs text-gray-400">สามารถเลือกได้หลายภาพ (รองรับ JPG, PNG, GIF)</p>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t">
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition']) ?>
        <?= Html::submitButton('💾 บันทึก', ['class' => 'px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-medium']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$inputClass = 'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 border';
$textareaClass = $inputClass . ' min-h-[80px]';
?>
<div class="max-w-4xl mx-auto bg-white shadow-sm rounded-xl p-6 border border-gray-200">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-5', 'enctype' => 'multipart/form-data']]); ?>

    <!-- ข้อมูลผลงาน -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📄 ข้อมูลผลงาน</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผลงาน <span
                        class="text-red-500">*</span></label>
                <?= $form->field($model, 'title')->textarea(['class' => $textareaClass, 'rows' => 3])->label(false) ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">พ.ศ.</label>
                    <?= $form->field($model, 'year')->textInput(['class' => $inputClass, 'placeholder' => 'เช่น 2559'])->label(false) ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานะผลงาน</label>
                    <?= $form->field($model, 'work_status')->dropDownList([
                        'ตีพิมพ์' => 'ตีพิมพ์',
                        'อยู่ระหว่างดำเนินการ' => 'อยู่ระหว่างดำเนินการ',
                        'เสร็จสิ้น' => 'เสร็จสิ้น',
                    ], ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ระยะเวลาดำเนินการ</label>
                    <?= $form->field($model, 'duration')->textInput(['class' => $inputClass])->label(false) ?>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อเจ้าของผลงาน</label>
                <?= $form->field($model, 'authors')->textInput(['class' => $inputClass, 'placeholder' => 'เช่น ดร.อรทัย นนทเภท / เกียรติกำจร กุศล'])->label(false) ?>
            </div>
        </div>
    </div>

    <!-- แหล่งทุน -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">💰 แหล่งทุน</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">สถานะแหล่งทุน</label>
                <?= $form->field($model, 'funding_status')->dropDownList([
                    'ภายใน' => 'ภายใน',
                    'ภายนอก' => 'ภายนอก',
                ], ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">งบประมาณ (บาท)</label>
                <?= $form->field($model, 'budget')->textInput(['class' => $inputClass, 'type' => 'number', 'step' => '0.01', 'placeholder' => '0.00'])->label(false) ?>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อแหล่งทุน</label>
            <?= $form->field($model, 'funding_source')->textInput(['class' => $inputClass])->label(false) ?>
        </div>
    </div>

    <!-- การเผยแพร่ -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📢 การเผยแพร่</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เผยแพร่ระดับ</label>
                <?= $form->field($model, 'publish_level')->dropDownList([
                    'ระดับชาติ' => 'ระดับชาติ',
                    'ระดับนานาชาติ' => 'ระดับนานาชาติ',
                ], ['class' => $inputClass, 'prompt' => '-- เลือก --'])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ระดับชั้น</label>
                <?= $form->field($model, 'tier')->textInput(['class' => $inputClass, 'placeholder' => 'เช่น กลุ่ม 1 TCT Impact Factor .227'])->label(false) ?>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">ผลการดำเนินการ/แหล่งเผยแพร่</label>
            <?= $form->field($model, 'result_publication')->textarea(['class' => $textareaClass, 'rows' => 3])->label(false) ?>
        </div>
    </div>

    <!-- พิกัดตำแหน่ง -->
    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">📍 พิกัดตำแหน่ง</h2>
        <p class="text-sm text-gray-500 mb-3">คลิกบนแผนที่เพื่อปักหมุดตำแหน่ง หรือกรอกพิกัดด้านล่าง</p>
        <div id="map-research" class="w-full h-80 rounded-lg border border-gray-300 mb-4 z-0"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ละติจูด</label>
                <?= $form->field($model, 'latitude')->textInput(['class' => $inputClass, 'type' => 'number', 'step' => '0.0000001', 'placeholder' => 'เช่น 13.7563309', 'id' => 'research-lat-input'])->label(false) ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ลองจิจูด</label>
                <?= $form->field($model, 'longitude')->textInput(['class' => $inputClass, 'type' => 'number', 'step' => '0.0000001', 'placeholder' => 'เช่น 100.5017651', 'id' => 'research-lng-input'])->label(false) ?>
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

            var map = L.map('map-research').setView([defaultLat, defaultLng], zoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            var marker = null;
            if (hasCoords) {
                marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
                marker.on('dragend', function (e) {
                    var pos = e.target.getLatLng();
                    document.getElementById('research-lat-input').value = pos.lat.toFixed(7);
                    document.getElementById('research-lng-input').value = pos.lng.toFixed(7);
                });
            }

            map.on('click', function (e) {
                var lat = e.latlng.lat.toFixed(7);
                var lng = e.latlng.lng.toFixed(7);
                document.getElementById('research-lat-input').value = lat;
                document.getElementById('research-lng-input').value = lng;
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng, { draggable: true }).addTo(map);
                    marker.on('dragend', function (ev) {
                        var pos = ev.target.getLatLng();
                        document.getElementById('research-lat-input').value = pos.lat.toFixed(7);
                        document.getElementById('research-lng-input').value = pos.lng.toFixed(7);
                    });
                }
            });

            ['research-lat-input', 'research-lng-input'].forEach(function (id) {
                document.getElementById(id).addEventListener('change', function () {
                    var lat = parseFloat(document.getElementById('research-lat-input').value);
                    var lng = parseFloat(document.getElementById('research-lng-input').value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        var latlng = L.latLng(lat, lng);
                        map.setView(latlng, 15);
                        if (marker) {
                            marker.setLatLng(latlng);
                        } else {
                            marker = L.marker(latlng, { draggable: true }).addTo(map);
                            marker.on('dragend', function (ev) {
                                var pos = ev.target.getLatLng();
                                document.getElementById('research-lat-input').value = pos.lat.toFixed(7);
                                document.getElementById('research-lng-input').value = pos.lng.toFixed(7);
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
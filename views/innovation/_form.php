<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Innovation $model */
?>

<div class="max-w-4xl mx-auto py-8">
    <div class="flex items-center space-x-3 mb-8">
        <?= Html::a('←', ['index'], ['class' => 'text-gray-400 hover:text-gray-600 text-2xl transition-colors']) ?>
        <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">
            <?= Html::encode($this->title) ?>
        </h1>
    </div>

    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide'],
                'inputOptions' => ['class' => 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none bg-gray-50/30'],
                'errorOptions' => ['class' => 'text-sm text-red-500 mt-1'],
            ],
        ]); ?>

        <div class="p-8 space-y-8">
            <!-- Section: Primary Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'ระบุชื่อนวัตกรรม']) ?>
                </div>
                <?= $form->field($model, 'invention_date')->textInput(['type' => 'date']) ?>
                <?= $form->field($model, 'advisor')->textInput(['placeholder' => 'ระบุชื่ออาจารย์ที่ปรึกษา']) ?>
            </div>

            <hr class="border-gray-100">

            <!-- Section: Detailed Info -->
            <div class="space-y-6">
                <?= $form->field($model, 'problem')->textarea(['rows' => 4, 'placeholder' => 'ระบุสถานการณ์หรือปัญหาที่ก่อให้เกิดนวัตกรรม']) ?>
                <?= $form->field($model, 'process')->textarea(['rows' => 4, 'placeholder' => 'ระบุกระบวนการนวัตกรรม']) ?>
                <?= $form->field($model, 'results')->textarea(['rows' => 4, 'placeholder' => 'ระบุผลลัพธ์/การนำไปใช้']) ?>
                <?= $form->field($model, 'developers')->textarea(['rows' => 3, 'placeholder' => 'ระบุรายชื่อผู้พัฒนานวัตกรรม']) ?>
            </div>

            <hr class="border-gray-100">

            <!-- Section: Location -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">ตำแหน่งที่ตั้ง
                    (พิกัด)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <?= $form->field($model, 'latitude')->textInput(['id' => 'lat', 'placeholder' => 'Latitude']) ?>
                    <?= $form->field($model, 'longitude')->textInput(['id' => 'lng', 'placeholder' => 'Longitude']) ?>
                </div>

                <!-- Leaflet Map -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <div id="map"
                    class="h-80 w-full rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden z-0">
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var lat = <?= $model->latitude ?: 8.441 ?>;
                        var lng = <?= $model->longitude ?: 99.913 ?>;
                        var zoom = <?= $model->latitude ? 15 : 6 ?>;

                        var map = L.map('map').setView([lat, lng], zoom);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                        var marker;
                        if (<?= $model->latitude ? 'true' : 'false' ?>) {
                            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                        }

                        map.on('click', function (e) {
                            var coords = e.latlng;
                            if (marker) {
                                marker.setLatLng(coords);
                            } else {
                                marker = L.marker(coords, { draggable: true }).addTo(map);
                            }
                            document.getElementById('lat').value = coords.lat.toFixed(8);
                            document.getElementById('lng').value = coords.lng.toFixed(8);
                        });

                        // Sync inputs to marker
                        ['lat', 'lng'].forEach(id => {
                            document.getElementById(id).addEventListener('change', function () {
                                var newLat = parseFloat(document.getElementById('lat').value);
                                var newLng = parseFloat(document.getElementById('lng').value);
                                if (!isNaN(newLat) && !isNaN(newLng)) {
                                    if (marker) marker.setLatLng([newLat, newLng]);
                                    else marker = L.marker([newLat, newLng], { draggable: true }).addTo(map);
                                    map.panTo([newLat, newLng]);
                                }
                            });
                        });
                    });
                </script>
            </div>

            <hr class="border-gray-100">

            <!-- Section: Files -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-indigo-700 mb-2 uppercase tracking-wide italic">📁
                        ไฟล์แนบ (เอกสาร)</label>
                    <input type="file" name="attachments[]" multiple
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-gray-200 p-2 rounded-xl">

                    <?php if (!$model->isNewRecord && count($model->attachments) > 0): ?>
                        <div class="mt-4 space-y-2">
                            <?php foreach ($model->attachments as $file): ?>
                                <div class="flex items-center justify-between text-xs bg-gray-50 p-2 rounded-lg group">
                                    <span class="truncate pr-2">
                                        <?= Html::encode($file->original_name) ?>
                                    </span>
                                    <a href="<?= Url::to(['delete-file', 'id' => $model->id, 'fileId' => $file->id]) ?>"
                                        class="text-red-400 opacity-0 group-hover:opacity-100 transition-opacity font-bold"
                                        data-confirm="ลบไฟล์นี้?" data-method="post">×</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-bold text-emerald-700 mb-2 uppercase tracking-wide italic">🖼
                        รูปภาพ (แกลเลอรี)</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all border border-gray-200 p-2 rounded-xl">

                    <?php if (!$model->isNewRecord && count($model->images) > 0): ?>
                        <div class="mt-4 grid grid-cols-4 gap-2">
                            <?php foreach ($model->images as $file): ?>
                                <div class="relative group aspect-square">
                                    <img src="<?= Url::to('@web/' . $file->file_path) ?>"
                                        class="w-full h-full object-cover rounded-lg">
                                    <a href="<?= Url::to(['delete-file', 'id' => $model->id, 'fileId' => $file->id]) ?>"
                                        class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow-sm opacity-0 group-hover:opacity-100 transition-opacity"
                                        data-confirm="ลบรูปภาพนี้?" data-method="post">×</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bg-gray-50/50 px-8 py-6 border-t border-gray-100 flex justify-end space-x-4">
            <?= Html::a('ยกเลิก', ['index'], ['class' => 'px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest']) ?>
            <?= Html::submitButton('บันทึกข้อมูลนวัตกรรม', ['class' => 'px-10 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform active:scale-95 uppercase tracking-widest']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
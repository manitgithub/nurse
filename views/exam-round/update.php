<?php
use yii\helpers\Html;
$this->title = 'แก้ไขรอบสอบ: ปี ' . $model->year . ' รอบ ' . $model->round_number;
?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">✏️
        <?= Html::encode($this->title) ?>
    </h1>
</div>
<?= $this->render('_form', ['model' => $model]) ?>
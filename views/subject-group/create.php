<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubjectGroup */

$this->title = 'เพิ่มสาขาตามโครงสร้าง';
?>
<div class="subject-group-create">

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">➕ <?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
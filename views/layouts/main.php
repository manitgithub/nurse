<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-full bg-gray-50">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <?php $this->head() ?>
</head>

<body class="h-full">
    <?php $this->beginBody() ?>

    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 shadow-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <a href="<?= Yii::$app->homeUrl ?>" class="flex items-center space-x-2">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                            </svg>
                            <span class="text-white font-bold text-lg">ระบบสถิติสำนักพยาบาล</span>
                        </a>
                        <div class="hidden md:block ml-10">
                            <div class="flex items-baseline space-x-1">
                                <a href="<?= \yii\helpers\Url::to(['/dashboard/index']) ?>"
                                    class="text-white hover:bg-white/20 rounded-md px-3 py-2 text-sm font-medium transition">แดชบอร์ด</a>

                                <!-- Students Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="text-indigo-100 hover:bg-white/20 hover:text-white rounded-md px-3 py-2 text-sm font-medium transition">นักศึกษา
                                        ▾</button>
                                    <div
                                        class="absolute left-0 mt-0 w-48 rounded-md shadow-lg bg-white ring-1 ring-black/5 hidden group-hover:block z-50">
                                        <a href="<?= \yii\helpers\Url::to(['/student/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">ข้อมูลนักศึกษา</a>
                                        <a href="<?= \yii\helpers\Url::to(['/exam-round/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">รอบสอบ</a>
                                        <a href="<?= \yii\helpers\Url::to(['/exam-result/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">ผลสอบ</a>
                                    </div>
                                </div>

                                <!-- Personnel Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="text-indigo-100 hover:bg-white/20 hover:text-white rounded-md px-3 py-2 text-sm font-medium transition">บุคลากร
                                        ▾</button>
                                    <div
                                        class="absolute left-0 mt-0 w-48 rounded-md shadow-lg bg-white ring-1 ring-black/5 hidden group-hover:block z-50">
                                        <a href="<?= \yii\helpers\Url::to(['/personnel/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">ข้อมูลบุคลากร</a>
                                        <a href="<?= \yii\helpers\Url::to(['/scholarship/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">นักเรียนทุน</a>
                                        <a href="<?= \yii\helpers\Url::to(['/academic-recruitment-plan/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">แผนอัตรากำลัง</a>
                                    </div>
                                </div>

                                <!-- Work Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="text-indigo-100 hover:bg-white/20 hover:text-white rounded-md px-3 py-2 text-sm font-medium transition">ผลงาน
                                        ▾</button>
                                    <div
                                        class="absolute left-0 mt-0 w-48 rounded-md shadow-lg bg-white ring-1 ring-black/5 hidden group-hover:block z-50">
                                        <a href="<?= \yii\helpers\Url::to(['/academic-service/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">บริการวิชาการ</a>
                                        <a href="<?= \yii\helpers\Url::to(['/research/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">งานวิจัย</a>
                                    </div>
                                </div>

                                <!-- Master Data Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="text-indigo-100 hover:bg-white/20 hover:text-white rounded-md px-3 py-2 text-sm font-medium transition">ข้อมูลหลัก
                                        ▾</button>
                                    <div
                                        class="absolute left-0 mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black/5 hidden group-hover:block z-50">
                                        <a href="<?= \yii\helpers\Url::to(['/qualification/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">คุณวุฒิ</a>
                                        <a href="<?= \yii\helpers\Url::to(['/contract-type/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">ประเภทสัญญา</a>
                                        <a href="<?= \yii\helpers\Url::to(['/department/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">สาขา</a>
                                        <a href="<?= \yii\helpers\Url::to(['/certification-level/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">ระดับใบรับรอง</a>
                                        <a href="<?= \yii\helpers\Url::to(['/expertise/index']) ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">ความเชี่ยวชาญ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a href="<?= \yii\helpers\Url::to(['/site/login']) ?>"
                                class="text-white hover:bg-white/20 rounded-md px-4 py-2 text-sm font-medium transition">เข้าสู่ระบบ</a>
                        <?php else: ?>
                            <span class="text-indigo-100 text-sm mr-3"><?= Yii::$app->user->identity->username ?></span>
                            <?= Html::beginForm(['/site/logout'], 'post') ?>
                            <?= Html::submitButton('ออกจากระบบ', ['class' => 'text-white bg-white/20 hover:bg-white/30 rounded-md px-4 py-2 text-sm font-medium transition']) ?>
                            <?= Html::endForm() ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <?= Alert::widget() ?>
            <?= $content ?>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">&copy; <?= date('Y') ?> ระบบสถิติและจัดการข้อมูลสำนักพยาบาล
                </p>
            </div>
        </footer>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
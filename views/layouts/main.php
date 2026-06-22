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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
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
                        <?php
                        $controllerId = Yii::$app->controller->id;
                        $isDashboard = ($controllerId === 'dashboard');
                        $isBudget = ($controllerId === 'budget');
                        $isStudent = in_array($controllerId, ['student', 'student-grade', 'exam-round', 'exam-result']);
                        $isPersonnel = in_array($controllerId, ['personnel', 'certification', 'scholarship', 'academic-recruitment-plan']);
                        $isWork = in_array($controllerId, ['academic-service', 'research', 'innovation']);
                        $isMaster = in_array($controllerId, ['qualification', 'contract-type', 'department', 'subject-group', 'certification-level', 'expertise']);
                        ?>
                        <div class="hidden md:block ml-10">
                            <div class="flex items-baseline space-x-1">
                                <!-- Dashboard -->
                                <a href="<?= \yii\helpers\Url::to(['/dashboard/index']) ?>"
                                    class="<?= $isDashboard ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/20 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium transition flex items-center whitespace-nowrap space-x-1.5 group">
                                    <svg class="w-5 h-5 <?= $isDashboard ? 'text-white' : 'text-indigo-200 group-hover:text-white' ?> transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                    <span>แดชบอร์ด</span>
                                </a>

                                <!-- Budget -->
                                <a href="<?= \yii\helpers\Url::to(['/budget/index']) ?>"
                                    class="<?= $isBudget ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/20 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium transition flex items-center whitespace-nowrap space-x-1.5 group">
                                    <svg class="w-5 h-5 <?= $isBudget ? 'text-white' : 'text-indigo-200 group-hover:text-white' ?> transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 012.625 18V5.625C2.625 5.004 3.129 4.5 3.75 4.5zM9 9h.008v.008H9V9zm.008 3h-.008v-.008h.008V12zm3-3h.008v.008h-.008V9zm.008 3h-.008v-.008h.008V12zm3-3h.008v.008h-.008V9zm.008 3h-.008v-.008h.008V12z" />
                                    </svg>
                                    <span>รายรับ-รายจ่าย</span>
                                </a>

                                <!-- Students Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="<?= $isStudent ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/20 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium transition flex items-center whitespace-nowrap space-x-1.5 group">
                                        <svg class="w-5 h-5 <?= $isStudent ? 'text-white' : 'text-indigo-200 group-hover:text-white' ?> transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                                        </svg>
                                        <span>นักศึกษา</span>
                                        <span class="text-xs opacity-75">▾</span>
                                    </button>
                                    <div
                                        class="absolute left-0 mt-2 w-56 rounded-xl shadow-xl bg-white border border-gray-100 p-1.5 hidden group-hover:block z-50 transition-all duration-200">
                                        <a href="<?= \yii\helpers\Url::to(['/student/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                            <span>ข้อมูลนักศึกษา</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/student-grade/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-indigo-600 hover:bg-indigo-50/70 rounded-lg transition group font-medium">
                                            <svg class="w-4 h-4 mr-2.5 text-indigo-500 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                            </svg>
                                            <span>ผลการเรียน (GPAX)</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/exam-round/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                            </svg>
                                            <span>รอบสอบ</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/exam-result/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0112 3.75c.38.04.758.085 1.13.135m-7.57 0h3.5" />
                                            </svg>
                                            <span>ผลสอบ</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/exam-result/statistics']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v16.5M21 19.5H3.75M21 5.25L13.5 12.75 10.5 9.75 4.5 15.75" />
                                            </svg>
                                            <span>สถิติการสอบ</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Personnel Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="<?= $isPersonnel ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/20 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium transition flex items-center whitespace-nowrap space-x-1.5 group">
                                        <svg class="w-5 h-5 <?= $isPersonnel ? 'text-white' : 'text-indigo-200 group-hover:text-white' ?> transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0z" />
                                        </svg>
                                        <span>บุคลากร</span>
                                        <span class="text-xs opacity-75">▾</span>
                                    </button>
                                    <div
                                        class="absolute left-0 mt-2 w-64 rounded-xl shadow-xl bg-white border border-gray-100 p-1.5 hidden group-hover:block z-50 transition-all duration-200">
                                        <a href="<?= \yii\helpers\Url::to(['/personnel/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                            <span>ข้อมูลบุคลากร</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/certification/batch-create']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm font-medium text-amber-600 hover:bg-amber-50/70 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-amber-500 group-hover:text-amber-700 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>บันทึก UKPSF แบบกลุ่ม</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/scholarship/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                            </svg>
                                            <span>นักเรียนทุน</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/academic-recruitment-plan/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0112 3.75c.38.04.758.085 1.13.135m-7.57 0h3.5" />
                                            </svg>
                                            <span>แผนอัตรากำลัง</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Work Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="<?= $isWork ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/20 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium transition flex items-center whitespace-nowrap space-x-1.5 group">
                                        <svg class="w-5 h-5 <?= $isWork ? 'text-white' : 'text-indigo-200 group-hover:text-white' ?> transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                        <span>ผลงาน</span>
                                        <span class="text-xs opacity-75">▾</span>
                                    </button>
                                    <div
                                        class="absolute left-0 mt-2 w-72 rounded-xl shadow-xl bg-white border border-gray-100 p-1.5 hidden group-hover:block z-50 transition-all duration-200">
                                        <a href="<?= \yii\helpers\Url::to(['/academic-service/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                                            </svg>
                                            <span class="truncate">บริการวิชาการ/ทำนุบำรุงวัฒนธรรม</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/research/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v1.244c0 .556-.06 1.119-.18 1.666-.12.548-.28 1.082-.475 1.597a7.597 7.597 0 00-1.398 2.227c-.276.446-.51.931-.7 1.442a12.115 12.115 0 00-.475 1.597c-.12.547-.18 1.11-.18 1.666V18c0 .828.672 1.5 1.5 1.5h10.5c.828 0 1.5-.672 1.5-1.5v-3.348c0-.556-.06-1.11-.18-1.666a12.122 12.122 0 00-.475-1.597 7.594 7.594 0 00-1.398-2.227c-.276-.446-.51-.931-.7-1.442a12.13 12.13 0 00-.475-1.597c-.12-.547-.18-1.11-.18-1.666V3.104" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 14h12M8 3h8" />
                                            </svg>
                                            <span>งานวิจัย</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/innovation/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm font-semibold text-indigo-600 hover:bg-indigo-50/70 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-indigo-500 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v3.75m0 0h.008v.008H12v-.008zm0-3.75a6 6 0 00-6-6v-1.5m6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                                            </svg>
                                            <span>นวัตกรรม</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Master Data Dropdown -->
                                <div class="relative group">
                                    <button
                                        class="<?= $isMaster ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/20 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium transition flex items-center whitespace-nowrap space-x-1.5 group">
                                        <svg class="w-5 h-5 <?= $isMaster ? 'text-white' : 'text-indigo-200 group-hover:text-white' ?> transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h1.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a6.723 6.723 0 0 1 0 .252c-.008.379.137.751.43.992l1.003.828c.435.36.564.977.26 1.43l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.216-.456a1.125 1.125 0 0 0-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-1.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.645-.87a6.52 6.52 0 0 1-.22-.127a1.125 1.125 0 0 0-1.075-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 1.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.252c.007-.379-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.49l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128c.332-.183.582-.495.645-.869l.214-1.28z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        </svg>
                                        <span>ข้อมูลหลัก</span>
                                        <span class="text-xs opacity-75">▾</span>
                                    </button>
                                    <div
                                        class="absolute right-0 mt-2 w-60 rounded-xl shadow-xl bg-white border border-gray-100 p-1.5 hidden group-hover:block z-50 transition-all duration-200">
                                        <a href="<?= \yii\helpers\Url::to(['/qualification/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                            </svg>
                                            <span>คุณวุฒิ</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/contract-type/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            <span>ประเภทสัญญา</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/department/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                            </svg>
                                            <span>สาขา</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/subject-group/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25V6z" />
                                            </svg>
                                            <span>สาขาตามโครงสร้าง</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/certification-level/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.75a1.125 1.125 0 01-1.125-1.125V11.25M9 3.75h6m-6 0a1.5 1.5 0 011.5-1.5h3A1.5 1.5 0 0115 3.75M9 3.75v11.25m6-11.25v11.25M9 3.75H7.5A1.5 1.5 0 006 5.25v8.25A1.5 1.5 0 007.5 15H9m6-11.25h1.5a1.5 1.5 0 011.5 1.5v8.25a1.5 1.5 0 01-1.5 1.5H15" />
                                            </svg>
                                            <span>ระดับใบรับรอง</span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['/expertise/index']) ?>"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50/70 hover:text-indigo-600 rounded-lg transition group">
                                            <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-indigo-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21m0 0l-.813-5.096M9 21h3.375c.621 0 1.125-.504 1.125-1.125V18M9 21H5.625M9 6.75a3 3 0 110-6 3 3 0 010 6zm0 0a3.375 3.375 0 100 6.75M9 13.5h.008v.008H9V13.5z" />
                                            </svg>
                                            <span>ความเชี่ยวชาญ</span>
                                        </a>
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

        <main class="mx-auto max-w-full py-6 px-4 sm:px-6 lg:px-8">
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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function initDatepickers() {
                const updateYear = function (instance) {
                    if (!instance.calendarContainer) return;
                    const yearInput = instance.calendarContainer.querySelector('.numInput.cur-year');
                    if (yearInput) {
                        yearInput.value = parseInt(instance.currentYear) + 543;
                    }
                };

                flatpickr('.datepicker-be', {
                    altInput: true,
                    altFormat: 'j M Y',
                    dateFormat: 'Y-m-d',
                    locale: 'th',
                    onReady: function (selectedDates, dateStr, instance) { updateYear(instance); },
                    onOpen: function (selectedDates, dateStr, instance) { updateYear(instance); },
                    onYearChange: function (selectedDates, dateStr, instance) { updateYear(instance); },
                    onMonthChange: function (selectedDates, dateStr, instance) { updateYear(instance); },
                    onValueUpdate: function (selectedDates, dateStr, instance) { updateYear(instance); },
                    formatDate: function (date, format, locale) {
                        if (format === 'Y-m-d') {
                            const y = date.getFullYear();
                            const m = String(date.getMonth() + 1).padStart(2, '0');
                            const d = String(date.getDate()).padStart(2, '0');
                            return `${y}-${m}-${d}`;
                        }
                        if (format === 'j M Y') {
                            const d = date.getDate();
                            const m = locale.months.shorthand[date.getMonth()];
                            const y = date.getFullYear() + 543;
                            return `${d} ${m} ${y}`;
                        }
                        const year_ce = date.getFullYear();
                        const year_be = year_ce + 543;
                        return flatpickr.formatDate(date, format).replace(year_ce.toString(), year_be.toString());
                    }
                });
            }
            initDatepickers();
            // Re-initialize if Alpine or Livewire DOM changes happen
            document.addEventListener('alpine:initialized', function () {
                // If there are dynamic fields
            });
        });
    </script>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
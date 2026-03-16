<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'เข้าสู่ระบบ';

// Use a blank layout or override styles
?>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        min-height: 100vh;
    }

    nav,
    footer,
    main>.container>nav {
        display: none !important;
    }

    footer {
        display: none !important;
    }

    main {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .login-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        outline: none;
        background: #f8fafc;
    }

    .login-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        background: #fff;
    }

    .login-btn {
        width: 100%;
        padding: 0.8rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        letter-spacing: 0.025em;
    }

    .login-btn:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        transform: translateY(-1px);
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
    }

    .login-btn:active {
        transform: translateY(0);
    }

    .field-loginform-username .help-block,
    .field-loginform-password .help-block,
    .field-loginform-rememberme .help-block {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .form-group {
        margin-bottom: 0;
    }
</style>

<div class="w-full max-w-md mx-auto px-4">
    <!-- Card -->
    <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20"
        style="animation: fadeInUp 0.6s ease-out;">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg mb-4"
                style="animation: pulse 2s ease-in-out infinite;">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">ระบบสถิติสำนักพยาบาล</h1>
            <p class="text-gray-500 text-sm mt-1">กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
        </div>

        <!-- Form -->
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700 mb-1.5'],
                'inputOptions' => ['class' => 'login-input'],
                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
            ],
            'options' => ['class' => 'space-y-5'],
        ]); ?>

        <div>
            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => 'ชื่อผู้ใช้',
                'class' => 'login-input',
            ])->label('ชื่อผู้ใช้') ?>
        </div>

        <div>
            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => '••••••••',
                'class' => 'login-input',
            ])->label('รหัสผ่าน') ?>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2 cursor-pointer select-none">
                <?= Html::activeCheckbox($model, 'rememberMe', [
                    'label' => false,
                    'class' => 'w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer',
                ]) ?>
                <span class="text-sm text-gray-600">จดจำการเข้าสู่ระบบ</span>
            </label>
        </div>

        <div>
            <?= Html::submitButton('เข้าสู่ระบบ', [
                'class' => 'login-btn',
                'name' => 'login-button',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>



        <!-- Footer -->
        <p class="text-center text-white/60 text-sm mt-6">&copy; <?= date('Y') ?> ระบบสถิติและจัดการข้อมูลสำนักพยาบาล
        </p>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
            }

            50% {
                box-shadow: 0 10px 25px -3px rgba(99, 102, 241, 0.5);
            }
        }
    </style>
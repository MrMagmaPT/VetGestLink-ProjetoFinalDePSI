<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Login';
?>
<style>
    /* Layout geral */
    body, html {
        height: 100%;
        margin: 0;
    }
    .login-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #0f2230 0%, #123a47 60%, #0b2b36 100%);
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #0b1b26;
    }

    /* Cartão */
    .login-card {
        width: 520px;
        max-width: calc(100% - 40px);
        background: #ffffff;
        border-radius: 14px;
        padding: 42px 48px;
        box-shadow: 0 10px 30px rgba(2,10,23,0.45);
        text-align: center;
    }

    /* Logotipo circular */
    .brand-circle {
        width: 72px;
        height: 72px;
        background: #14c0d8;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        box-shadow: 0 6px 18px rgba(20,192,216,0.18);
    }

    /* Títulos */
    .login-title {
        font-size: 26px;
        font-weight: 600;
        color: #0b1b26;
        margin-bottom: 6px;
    }
    .login-sub {
        color: #6b7a86;
        margin-bottom: 28px;
    }

    /* Campos */
    .form-label {
        display: block;
        text-align: left;
        font-weight: 600;
        color: #26343e;
        margin-bottom: 8px;
    }
    .input-group .input-group-text {
        background: #f2f4f6;
        border: 0;
        color: #9aa6b0;
        width: 46px;
        justify-content: center;
        border-radius: 8px;
    }
    .form-control {
        border-radius: 8px;
        background: #f7f8f9;
        border: 0;
        height: 44px;
        padding-left: 12px;
        color: #22343d;
        box-shadow: none;
    }
    .input-group {
        gap: 10px;
    }
    .field {
        margin-bottom: 18px;
    }

    /* Botão */
    .btn-login {
        width: 100%;
        background: linear-gradient(180deg,#07050b 0%, #0d0a12 100%);
        color: #fff;
        border-radius: 10px;
        padding: 10px 18px;
        border: 0;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(3,3,6,0.45);
    }
    .btn-login:focus, .btn-login:hover {
        opacity: 0.95;
    }

    /* Flash */
    .alert-custom {
        text-align: left;
        margin-bottom: 18px;
    }

    /* Responsivo */
    @media (max-width: 576px) {
        .login-card { padding: 28px 20px; }
        .brand-circle { width: 60px; height: 60px; }
        .login-title { font-size: 20px; }
    }
</style>

<div class="login-bg">
    <div class="login-card">
        <div class="brand-circle" aria-hidden="true">
            <!-- ícone simples (pata) em SVG -->
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="logo">
                <path d="M6.5 3.5C6.5 5.20914 5.20914 6.5 3.5 6.5C1.79086 6.5 0.5 5.20914 0.5 3.5C0.5 1.79086 1.79086 0.5 3.5 0.5C5.20914 0.5 6.5 1.79086 6.5 3.5Z" transform="translate(3 3)" fill="#ffffff" opacity="0.98"/>
                <path d="M12 7.5C14.4853 7.5 16.5 9.51472 16.5 12C16.5 14.4853 14.4853 16.5 12 16.5C9.51472 16.5 7.5 14.4853 7.5 12C7.5 9.51472 9.51472 7.5 12 7.5Z" transform="translate(0 1)" fill="#ffffff"/>
                <path d="M2 1C3.10457 1 4 1.89543 4 3C4 4.10457 3.10457 5 2 5C0.89543 5 0 4.10457 0 3C0 1.89543 0.89543 1 2 1Z" transform="translate(16 6)" fill="#ffffff"/>
                <path d="M2 1C3.10457 1 4 1.89543 4 3C4 4.10457 3.10457 5 2 5C0.89543 5 0 4.10457 0 3C0 1.89543 0.89543 1 2 1Z" transform="translate(20 3)" fill="#ffffff"/>
            </svg>
        </div>

        <h2 class="login-title"><?= Html::encode('VetCare Sistema de Gestão') ?></h2>
        <div class="login-sub">Entre para aceder ao sistema</div>

        <?php if (Yii::$app->session->hasFlash('showFrontendButton')): ?>
            <div class="alert alert-danger alert-dismissible alert-custom">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                Acesso negado, área restrita somente a funcionários.
                <hr>
                <a href="<?= Yii::$app->params['frontendUrl'] ?>" class="btn btn-primary w-100 mt-2">
                    <i class="bi bi-house-door"></i> Área de Clientes
                </a>
            </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['autocomplete' => 'off'],
            'fieldConfig' => ['options' => ['class' => 'field']],
        ]); ?>

        <?= $form->field($model, 'username', [
            'template' => "{label}\n<div class=\"input-group\"> <span class=\"input-group-text\"><i class=\"bi bi-envelope-fill\"></i></span>{input}</div>\n{error}",
            'labelOptions' => ['class' => 'form-label']
        ])->textInput(['autofocus' => true, 'placeholder' => 'admin, vet, recep ou email', 'class' => 'form-control'])->label('Utilizador / Email') ?>

        <?= $form->field($model, 'password', [
            'template' => "{label}\n<div class=\"input-group\"> <span class=\"input-group-text\"><i class=\"bi bi-lock-fill\"></i></span>{input}</div>\n{error}",
            'labelOptions' => ['class' => 'form-label']
        ])->passwordInput(['placeholder' => 'Introduza a sua palavra-passe', 'class' => 'form-control'])->label('Palavra-passe') ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"form-check form-switch mb-3\">{input}\n{label}</div>\n{error}",
            'labelOptions' => ['class' => 'form-check-label ms-2']
        ]) ?>

        <div class="mt-3">
            <?= Html::submitButton('Entrar', ['class' => 'btn-login', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

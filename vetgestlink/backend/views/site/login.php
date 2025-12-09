<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Login';

//Pega o favicon global definido nos aliases
$faviconUrl = Yii::getAlias('@web') . '/favicon.ico';
//Tive que adicionar este CSS inline pq o layout.css nao estava a ser carregado corretamente no login

?>

<div class="login-bg d-flex align-items-center">
    <div class="login-center-box" style="max-width:380px;min-width:300px;width:100%;margin:0 auto;background:#fff; border-radius:24px; box-shadow:0 8px 32px rgba(30,42,58,0.18); padding:20px 20px;">
        <div class="login-icon ">
            <img src="<?= $faviconUrl ?>" alt="Logo" style="width:72px;height:72px;display:block;margin:0 auto;">
        </div>
        <h2 class="login-title" style="font-size:2rem; font-weight:600; margin-bottom:8px; color:#222b45; text-align:center;">VetGestLink</h2>
        <p class="login-subtitle" style="font-size:1.08rem; color:#6c7a89; text-align:center; margin-bottom:24px;">Entre para aceder ao sistema</p>
        <?php if (Yii::$app->session->hasFlash('showFrontendButton')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:0.92rem; padding:12px 10px;">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                Acesso negado, área para funcionários.  
                <a href="<?= Yii::$app->params['frontendUrl'] ?>" class="btn btn-primary w-100 mt-2" style="font-size:0.92rem; padding:6px 0;">
                    <i class="bi bi-house-door"></i>Área de Clientes
                </a>
            </div>
        <?php endif; ?>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="login-form-group">
                <?= $form->field($model, 'username')->textInput(['placeholder' => 'admin, vet, recep ...', 'autofocus' => true])->label('Username') ?>
            </div>
            <div class="login-form-group">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Introduza a sua palavra-passe'])->label('Palavra-passe') ?>
            </div>
            <div class="login-form-group">
                <?= $form->field($model, 'rememberMe')->checkbox(['label' => 'Lembrar-se de mim'])  ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Entrar', ['class' => 'btn btn-dark w-100', 'name' => 'login-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
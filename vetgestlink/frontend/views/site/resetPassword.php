<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Redefinir Senha';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-lg-5 col-md-7 col-sm-9">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    
                    <!-- Header com ícone -->
                    <div class="text-center mb-4">
                        <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-key fa-2x text-white"></i>
                        </div>
                        <h1 class="h3 fw-bold mb-2"><?= Html::encode($this->title) ?></h1>
                        <p class="text-muted small">Escolha uma nova senha forte para sua conta</p>
                    </div>

                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                        <?= $form->field($model, 'password')->passwordInput([
                            'autofocus' => true,
                            'placeholder' => 'Digite sua nova senha',
                            'class' => 'form-control form-control-lg'
                        ])->label('Nova Senha', ['class' => 'form-label fw-semibold']) ?>

                        <div class="d-grid gap-2 mb-3">
                            <?= Html::submitButton('<i class="fas fa-check-circle me-2"></i>Salvar Nova Senha', [
                                'class' => 'btn btn-primary btn-lg',
                                'name' => 'reset-button'
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                    <!-- Requisitos de senha -->
                    <div class="alert alert-info border-start border-primary border-4 mt-4" role="alert">
                        <h6 class="alert-heading fw-semibold">
                            <i class="fas fa-info-circle me-2"></i>Requisitos da Senha:
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li>Mínimo de 8 caracteres</li>
                            <li>Use letras maiúsculas e minúsculas</li>
                            <li>Inclua números</li>
                            <li>Use caracteres especiais (!@#$%)</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

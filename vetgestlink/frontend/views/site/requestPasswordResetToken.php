<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Recuperar Password';
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
                            <i class="fas fa-lock fa-2x text-white"></i>
                        </div>
                        <h1 class="h3 fw-bold mb-2"><?= Html::encode($this->title) ?></h1>
                        <p class="text-muted small">Insira o seu email abaixo. Será enviado um link para redefinir a sua password.</p>
                    </div>

                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                        <?= $form->field($model, 'email')->textInput([
                            'autofocus' => true,
                            'placeholder' => 'exemplo@email.com',
                            'class' => 'form-control form-control-lg',
                            'type' => 'email'
                        ])->label('Email', ['class' => 'form-label fw-semibold']) ?>

                        <div class="d-grid gap-2 mb-3">
                            <?= Html::submitButton('<i class="fas fa-paper-plane me-2"></i>Enviar Link de Recuperação', [
                                'class' => 'btn btn-primary btn-lg'
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                    <!-- Informação adicional -->
                    <div class="alert alert-info border-start border-primary border-4 mt-4" role="alert">
                        <h6 class="alert-heading fw-semibold mb-2">
                            <i class="fas fa-info-circle me-2"></i>Informação:
                        </h6>
                        <p class="small mb-1">
                            <i class="fas fa-check-circle me-1 text-success"></i> Verifique sua caixa de entrada
                        </p>
                        <p class="small mb-1">
                            <i class="fas fa-clock me-1 text-warning"></i> O link expira em 24 horas
                        </p>
                        <p class="small mb-0">
                            <i class="fas fa-shield-alt me-1 text-primary"></i> Não compartilhe o link com ninguém
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

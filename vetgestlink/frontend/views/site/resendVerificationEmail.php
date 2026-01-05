<?php

/** @var yii\web\View$this  */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResendVerificationEmailForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reenviar Email de Verificação';
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
                            <i class="fas fa-envelope-open-text fa-2x text-white"></i>
                        </div>
                        <h1 class="h3 fw-bold mb-2"><?= Html::encode($this->title) ?></h1>
                        <p class="text-muted small">Insira o seu email para receber um novo link de verificação da conta.</p>
                    </div>

                    <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

                        <?= $form->field($model, 'email')->textInput([
                            'autofocus' => true,
                            'placeholder' => 'exemplo@email.com',
                            'class' => 'form-control form-control-lg',
                            'type' => 'email'
                        ])->label('Email', ['class' => 'form-label fw-semibold']) ?>

                        <div class="d-grid gap-2 mb-3">
                            <?= Html::submitButton('<i class="fas fa-paper-plane me-2"></i>Reenviar Email de Verificação', [
                                'class' => 'btn btn-info btn-lg text-white'
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                    <!-- Informação adicional -->
                    <div class="alert alert-light border-start border-info border-4 mt-4" role="alert">
                        <h6 class="alert-heading fw-semibold mb-2">
                            <i class="fas fa-info-circle me-2 text-info"></i>Informação:
                        </h6>
                        <p class="small mb-1">
                            <i class="fas fa-check-circle me-1 text-success"></i> Verifique sua caixa de entrada e spam
                        </p>
                        <p class="small mb-1">
                            <i class="fas fa-user-check me-1 text-primary"></i> O link ativa sua conta
                        </p>
                        <p class="small mb-0">
                            <i class="fas fa-clock me-1 text-warning"></i> Aguarde alguns minutos antes de solicitar novamente
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Recuperar Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-9">
            <div class="card shadow-sm" style="margin-top: 50px;">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-key"></i> <?= Html::encode($this->title) ?></h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <p class="text-muted text-center mb-4">
                        Insira o seu email abaixo. Será enviado um link para redefinir a sua password.
                    </p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'request-password-reset-form',
                        'options' => ['class' => 'needs-validation']
                    ]); ?>

                        <?= $form->field($model, 'email')->textInput([
                            'autofocus' => true,
                            'placeholder' => 'exemplo@email.com',
                            'class' => 'form-control form-control-lg'
                        ])->label('<i class="fas fa-envelope"></i> Email', ['class' => 'form-label fw-bold']) ?>

                        <div class="d-grid gap-2 mt-4">
                            <?= Html::submitButton('<i class="fas fa-paper-plane"></i> Enviar Link', [
                                'class' => 'btn btn-primary btn-lg'
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

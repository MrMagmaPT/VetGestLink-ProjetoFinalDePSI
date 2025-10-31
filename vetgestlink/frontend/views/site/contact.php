<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contato';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-contact mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="mb-4 text-center"><?= Html::encode($this->title) ?></h1>

            <p class="text-center text-muted mb-4">
                Tem dúvidas ou sugestões? Preencha o formulário abaixo e entraremos em contato!
            </p>

            <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                <div class="alert alert-success">
                    Obrigado por entrar em contato. Responderemos em breve.
                </div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <div class="mb-3">
                <?= $form->field($model, 'name')->textInput(['class' => 'form-control', 'placeholder' => 'Seu nome'])->label(false) ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => 'Seu e-mail'])->label(false) ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'subject')->textInput(['class' => 'form-control', 'placeholder' => 'Assunto'])->label(false) ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'body')->textarea(['class' => 'form-control', 'placeholder' => 'Mensagem', 'rows' => 5])->label(false) ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-8">{input}</div></div>',
                        'options' => ['class' => 'form-control', 'placeholder' => 'Código de verificação'],
                ])->label(false) ?>
            </div>

            <div class="d-grid">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary btn-lg w-100']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

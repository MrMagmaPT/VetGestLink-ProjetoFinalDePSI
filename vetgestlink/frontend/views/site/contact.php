<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contato';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="contact-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="contact-card">
                    <div class="contact-header text-center">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h1 class="contact-title"><?= Html::encode($this->title) ?></h1>
                        <p class="contact-subtitle">
                            Tem dúvidas ou sugestões? Estamos aqui para ajudar!<br>
                            Preencha o formulário abaixo e entraremos em contato em breve.
                        </p>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'contact-form',
                        'fieldConfig' => [
                            'template' => '<div class="form-group">{label}{input}{error}</div>',
                        ]
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                                <?= $form->field($model, 'name')
                                    ->textInput([
                                        'class' => 'form-control',
                                        'placeholder' => 'Digite seu nome completo'
                                    ])
                                    ->label('<span class="form-label">Nome</span>') ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="input-icon">
                                <i class="fas fa-envelope"></i>
                                <?= $form->field($model, 'email')
                                    ->textInput([
                                        'class' => 'form-control',
                                        'placeholder' => 'seu@email.com'
                                    ])
                                    ->label('<span class="form-label">E-mail</span>') ?>
                            </div>
                        </div>
                    </div>

                    <div class="input-icon">
                        <i class="fas fa-tag"></i>
                        <?= $form->field($model, 'subject')
                            ->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Qual o assunto da sua mensagem?'
                            ])
                            ->label('<span class="form-label">Assunto</span>') ?>
                    </div>

                    <div class="input-icon">
                        <i class="fas fa-comment-alt" style="top: 25px;"></i>
                        <?= $form->field($model, 'body')
                            ->textarea([
                                'class' => 'form-control',
                                'placeholder' => 'Escreva sua mensagem aqui...',
                                'rows' => 6
                            ])
                            ->label('<span class="form-label">Mensagem</span>') ?>
                    </div>

                    <div class="d-grid mt-4">
                        <?= Html::submitButton(
                            '<i class="fas fa-paper-plane me-2"></i>Enviar Mensagem',
                            ['class' => 'btn btn-submit']
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

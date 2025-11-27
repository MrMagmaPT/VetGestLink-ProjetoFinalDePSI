<?php

/** @var yii\web\View $this */
/** @var backend\models\SignupFormBackend $model */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Registar';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <div class="panel panel-primary" style="max-width: 900px; margin: 30px auto;">
        <div class="panel-heading text-center" style="padding: 20px;">
            <h1 class="panel-title" style="font-size: 28px; font-weight: bold;">Registar utilizador</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Preencha os campos abaixo para criar uma conta</p>
        </div>

        <div class="panel-body" style="padding: 30px;">
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $messages): ?>
                <?php
                $alertType = ($type === 'error') ? 'danger' : $type;
                $messages = (array) $messages;
                ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php if (count($messages) > 1): ?>
                        <ul class="mb-0">
                            <?php foreach ($messages as $message): ?>
                                <li><?= Html::encode($message) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <?= Html::encode($messages[0]) ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php $form = ActiveForm::begin([
                    'id' => 'form-signup',
                    'options' => [
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data'
                    ],
                    'fieldConfig' => [
                            'template' => "{label}\n{input}\n<div class=\"text-danger\">{error}</div>",
                    ],
            ]); ?>

            <!-- Dados de Acesso -->
            <div class="panel panel-info" style="margin-bottom: 25px;">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-lock"></i> Dados de Acesso</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'username')->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control',
                                    'placeholder' => 'Digite seu nome de utilizador'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput([
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'placeholder' => 'seuemail@exemplo.com'
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Mínimo 6 caracteres'
                    ]) ?>
                </div>
            </div>

            <!-- Informações Pessoais -->
            <div class="panel panel-info" style="margin-bottom: 25px;">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Informações Pessoais</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'nomecompleto')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Nome completo'
                    ]) ?>

                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'dtanascimento')->input('date', [
                                    'class' => 'form-control',
                                    'max' => date('Y-m-d')
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'nif')->textInput([
                                    'class' => 'form-control',
                                    'maxlength' => 9,
                                    'placeholder' => '000000000'
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'telemovel')->textInput([
                                    'class' => 'form-control',
                                    'maxlength' => 9,
                                    'placeholder' => '900000000'
                            ]) ?>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <?= $form->field($model, 'role')->dropDownList(
                            array_map(function($role) {
                                return ucfirst($role->name);  //upercase da primeira letra da string
                            }, Yii::$app->authManager->getRoles()),
                            ['prompt' => 'Selecione a Role']
                        ) ?>
                    </div>                  

                    <div class="form-group">
                        <?= $form->field($model, 'imageFile')->fileInput([
                            'class' => 'form-control',
                            'accept' => 'image/png, image/jpeg, image/jpg'
                        ])->hint('Formatos aceites: PNG, JPG, JPEG (opcional)') ?>
                    </div>
                </div>
            </div>

            <!-- Morada -->
            <div class="panel panel-info" style="margin-bottom: 25px;">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-map-marker"></i> Morada</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'rua')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'Nome da rua'
                            ]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'nporta')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'Nº'
                            ]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'andar')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'Andar (opcional)'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'cdpostal')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => '0000-000'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cxpostal')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'Caixa Postal (opcional)'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'localidade')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'Localidade'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cidade')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'Cidade'
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'principal')->checkbox([
                            'label' => 'Definir como morada principal',
                            'checked' => true
                    ]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> Criar Conta', [
                    'class' => 'btn btn-primary btn-lg btn-block rounded-pill shadow-sm px-4',
                    'name' => 'signup-button'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="text-center" style="margin-top: 20px;">
                <?php if (YII_DEBUG): ?>
                    <button type="button" onclick="autoFill()">Auto Fill</button>

                    <script>
                        function autoFill() {
                            document.getElementById('signupform-email').value = 'user' + Math.floor(Math.random() * 10000) + '@example.com';
                            document.getElementById('signupform-nomecompleto').value = 'João Silva Santos';
                            document.getElementById('signupform-dtanascimento').value = '1990-05-15';
                            document.getElementById('signupform-nif').value = '123456789';
                            document.getElementById('signupform-telemovel').value = '912345678';
                            document.getElementById('signupform-rua').value = 'Rua das Flores';
                            document.getElementById('signupform-nporta').value = '123';
                            document.getElementById('signupform-andar').value = '2';
                            document.getElementById('signupform-cdpostal').value = '1234-567';
                            document.getElementById('signupform-cxpostal').value = '1000';
                            document.getElementById('signupform-localidade').value = 'Lisboa';
                            document.getElementById('signupform-cidade').value = 'Lisboa';
                        }
                    </script>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>

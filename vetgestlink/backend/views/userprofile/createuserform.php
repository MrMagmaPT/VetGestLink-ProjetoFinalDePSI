<?php

/** @var yii\web\View $this */
/** @var backend\models\SignupFormBackend $model */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Registar Utilizador';
$this->params['breadcrumbs'][] = ['label' => 'Utilizadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-user-plus text-primary"></i>
                    Registar Utilizador
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $messages): ?>
            <?php
            $alertType = ($type === 'error') ? 'danger' : $type;
            $messages = (array) $messages;
            ?>
            <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    'enctype' => 'multipart/form-data'
                ],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n<div class=\"text-danger\">{error}</div>",
                ],
        ]); ?>

        <div class="row">
            <!-- Coluna Esquerda -->
            <div class="col-md-8">
                <!-- Dados de Acesso -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-lock text-primary"></i>
                            Dados de Acesso
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'username')->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control',
                                    'placeholder' => 'Digite o nome de utilizador'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'email')->textInput([
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'placeholder' => 'email@exemplo.com'
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
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user text-info"></i>
                            Informações Pessoais
                        </h5>
                    </div>
                    <div class="card-body">
                        <?= $form->field($model, 'nomecompleto')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Nome completo do utilizador'
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

                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('admin')): ?>
                            <?= $form->field($model, 'role')->dropDownList(
                                array_map(function($role) {
                                    return ucfirst($role->name);
                                }, Yii::$app->authManager->getRoles()),
                                [
                                    'prompt' => 'Selecione a Role', 
                                    'class' => 'form-control',
                                    'options' => ['cliente' => ['selected' => true]]
                                ]
                            ) ?>
                        <?php endif; ?>

                        <div class="form-group">
                            <?= $form->field($model, 'imageFile')->fileInput([
                                'class' => 'form-control',
                                'accept' => 'image/png, image/jpeg, image/jpg'
                            ])->hint('<small class="text-muted"><i class="fas fa-info-circle"></i> Formatos aceites: PNG, JPG, JPEG (opcional)</small>') ?>
                        </div>
                    </div>
                </div>

                <!-- Morada -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt text-success"></i>
                            Morada
                        </h5>
                    </div>
                    <div class="card-body">
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
                                    'placeholder' => 'Andar'
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
                                    'placeholder' => 'Caixa Postal'
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

                        <div class="form-check">
                            <?= $form->field($model, 'principal')->checkbox([
                                'label' => 'Definir como morada principal',
                                'checked' => true,
                                'class' => 'form-check-input'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita - Ações e Ajuda -->
            <div class="col-md-4">
                <!-- Card Ações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tasks text-secondary"></i>
                            Ações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?= Html::submitButton(
                                '<i class="fas fa-check"></i> Criar Utilizador',
                                [
                                    'class' => 'btn btn-success btn-lg',
                                    'name' => 'signup-button'
                                ]
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-times"></i> Cancelar',
                                ['index'],
                                ['class' => 'btn btn-secondary btn-lg']
                            ) ?>
                        </div>
                    </div>
                </div>

                <!-- Card Ajuda -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-info"></i>
                            Ajuda
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading"><i class="fas fa-lock"></i> Dados de Acesso</h6>
                            <small>Username e password serão usados para acesso ao sistema.</small>
                        </div>
                        
                        <div class="alert alert-success mb-3">
                            <h6 class="alert-heading"><i class="fas fa-user"></i> Informações</h6>
                            <small>Preencha todos os dados pessoais do utilizador.</small>
                        </div>

                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('admin')): ?>
                            <div class="alert alert-warning mb-3">
                                <h6 class="alert-heading"><i class="fas fa-user-shield"></i> Role</h6>
                                <small>Defina as permissões do utilizador no sistema.</small>
                            </div>
                        <?php endif; ?>

                        <div class="alert alert-secondary mb-0">
                            <h6 class="alert-heading"><i class="fas fa-map-marker-alt"></i> Morada</h6>
                            <small>Endereço principal do utilizador.</small>
                        </div>
                    </div>
                </div>

                <?php if (YII_DEBUG): ?>
                    <!-- Card Debug -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">
                                <i class="fas fa-bug"></i>
                                Debug
                            </h5>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-warning w-100" onclick="autoFill()">
                                <i class="fas fa-magic"></i> Auto Preencher
                            </button>
                        </div>
                    </div>


                    <!--Debug Script-->
                    <script>
                        function autoFill() {
                            document.getElementById('signupformbackend-username').value = 'user' + Math.floor(Math.random() * 10000);
                            document.getElementById('signupformbackend-email').value = 'user' + Math.floor(Math.random() * 10000) + '@example.com';
                            document.getElementById('signupformbackend-password').value = '123456';
                            document.getElementById('signupformbackend-nomecompleto').value = 'João Silva Santos';
                            document.getElementById('signupformbackend-dtanascimento').value = '1990-05-15';
                            document.getElementById('signupformbackend-nif').value = '123456789' + Math.floor(Math.random() * 10000);
                            document.getElementById('signupformbackend-telemovel').value = '912345678';
                            document.getElementById('signupformbackend-rua').value = 'Rua das Flores';
                            document.getElementById('signupformbackend-nporta').value = '123';
                            document.getElementById('signupformbackend-andar').value = '2';
                            document.getElementById('signupformbackend-cdpostal').value = '1234-567';
                            document.getElementById('signupformbackend-cxpostal').value = '1000';
                            document.getElementById('signupformbackend-localidade').value = 'Lisboa';
                            document.getElementById('signupformbackend-cidade').value = 'Lisboa';
                        }
                    </script>
                <?php endif; ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

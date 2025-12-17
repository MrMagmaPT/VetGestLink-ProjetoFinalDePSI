<?php

/** @var yii\web\View $this */
/** @var frontend\models\SignupForm $model */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\ImageUploadWidget;

$this->title = 'Registar';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <div class="panel panel-primary" style="max-width: 900px; margin: 30px auto;">
        <div class="panel-heading text-center">
            <h1 class="panel-title"> <?= Html::encode($this->title) ?></h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Preencha os campos abaixo para criar a sua conta</p>
        </div>

        <div class="panel-body" style="padding: 30px;">


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

            <!-- Foto de Perfil -->
            <div class="panel panel-info" style="margin-bottom: 25px;">
                <div class="panel-heading text-center">
                    <h3 class="panel-title"><i class="fa fa-camera text-success"></i> Foto de Perfil</h3>
                </div>
                <div class="panel-body">
                    <?= ImageUploadWidget::widget([
                        'model' => $model,
                        'attribute' => 'imageFile',
                        'form' => $form,
                        'previewId' => 'image-preview-signup',
                        'defaultImage' => Yii::getAlias('@web') . '/uploads/users/default.jpg',
                        'imageSize' => 150,
                        'borderColor' => '#28a745',
                        'helpText' => 'Clique na imagem para adicionar sua foto',
                    ]) ?>
                </div>
            </div>

            <!-- Dados de Acesso -->
            <div class="panel panel-info" style="margin-bottom: 25px;">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-lock text-success"></i> Dados de Acesso</h3>
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
                    <h3 class="panel-title"><i class="fa fa-user text-success"></i> Informações Pessoais</h3>
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
                </div>
            </div>

            <!-- Morada -->
            <div class="panel panel-info" style="margin-bottom: 25px;">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-map-marker text-success"></i> Morada</h3>
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
                        'class' => 'btn btn btn-lg btn-block rounded-pill px-4',
                        'name' => 'signup-button'
                ]) ?>
            </div>


            <?php ActiveForm::end(); ?>

            <div class="text-center" style="margin-top: 20px;">
                <p class="text-muted">
                    Já tem uma conta? <?= Html::a('Entrar', ['site/login'], ['class' => 'btn btn btn-lg rounded-pill px-4']) ?>
                </p>

                //DEBUG LOG(REMOVER DEPOIS)
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

<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 class="text-center page-title"><?= Html::encode($this->title) ?></h1>

    <div class="site-login d-flex justify-content-center align-items-center" style="min-height: 75vh;">
        <div class="row w-100" style="max-width: 900px;">

            <!-- LEFT: LOGIN CARD -->
            <div class="col-lg-6 mb-4 d-flex">
                <div class="card shadow-lg border-0 rounded-4 flex-fill">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <h2 class="text-center mb-4">Bem-vindo!</h2>

                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username')
                            ->textInput(['autofocus' => true, 'placeholder' => 'Enter your username'])
                            ->label(false) ?>

                        <?= $form->field($model, 'password')
                            ->passwordInput(['placeholder' => 'Enter your password'])
                            ->label(false) ?>

                        <div class="d-flex justify-content-between mb-3">
                            <?= $form->field($model, 'rememberMe')->checkbox()->label('Remember me') ?>
                            <small>
                                <?= Html::a('Forgot Password?', ['site/request-password-reset'], ['class' => 'text-decoration-none']) ?>
                            </small>
                        </div>

                        <div class="d-grid mb-3 mt-auto">
                            <?= Html::submitButton('Login', [
                                'class' => 'btn btn btn-lg rounded-pill',
                                'name'  => 'login-button'
                            ]) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT: SIGNUP CARD -->
            <div class="col-lg-6 mb-4 d-flex">
                <div class="card shadow-lg border-0 rounded-4 flex-fill">
                    <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                        <h2 class="mb-3">Novo por aqui?</h2>
                        <p class="text-muted mb-4">
                            Crie uma conta para gerir os seus animas e reservar consultas.
                        </p>

                        <div class="d-grid mb-3 mt-auto">
                            <?= Html::a(
                                'Create Account',
                                ['site/signup'],
                                ['class' => 'btn btn btn-lg rounded-pill px-4']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


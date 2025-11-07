<?php

/** @var yii\web\View $this */
/** @var common\models\Userprofile $userProfile */
/** @var common\models\User $user */
/** @var common\models\Morada|null $morada */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Editar Perfil';
$this->params['breadcrumbs'][] = ['label' => 'Perfil', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
    :root {
        --accent-green: #00d100;
    }

    .edit-card {
        border-radius: 15px;
    }

    .section-title {
        font-weight: 700;
        color: var(--accent-green);
        border-left: 4px solid var(--accent-green);
        padding-left: 10px;
        margin-bottom: 15px;
    }

    .btn-accent {
        background-color: var(--accent-green);
        border-color: var(--accent-green);
        color: white;
        font-weight: bold;
    }

    .btn-accent:hover {
        background-color: #00b300;
        border-color: #00b300;
        color: white;
    }

    .form-control {
        border-radius: 8px !important;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow edit-card border-0">
                <div class="card-header text-center text-white" style="background: var(--accent-green)">
                    <h4 class="mb-0 fw-bold"><?= Html::encode($this->title) ?></h4>
                </div>

                <div class="card-body p-4">
                    <?php $form = ActiveForm::begin([
                        'action' => ['update'],
                        'method' => 'post'
                    ]); ?>

                    <!-- PROFILE -->
                    <h5 class="section-title">Informações pessoais</h5>

                    <?= $form->field($userProfile, 'nomecompleto')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($userProfile, 'nif')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($userProfile, 'telemovel')->textInput(['maxlength' => true]) ?>

                    <div class="mb-4"></div>

                    <!-- MORADA -->
                    <h5 class="section-title">Morada</h5>

                    <?php foreach ($moradas as $i => $morada): ?>
                        <div class="rounded p-3 mb-3 border">

                            <h6 class="fw-bold">Morada <?= $i + 1 ?></h6>

                            <?= $form->field($morada, "[$i]rua")->textInput(['maxlength' => true]) ?>
                            <?= $form->field($morada, "[$i]nporta")->textInput(['maxlength' => true]) ?>
                            <?= $form->field($morada, "[$i]andar")->textInput(['maxlength' => true]) ?>
                            <?= $form->field($morada, "[$i]cdpostal")->textInput(['maxlength' => true]) ?>
                            <?= $form->field($morada, "[$i]cidade")->textInput(['maxlength' => true]) ?>
                            <?= $form->field($morada, "[$i]cxpostal")->textInput(['maxlength' => true]) ?>
                            <?= $form->field($morada, "[$i]localidade")->textInput(['maxlength' => true]) ?>

                        </div>
                    <?php endforeach; ?>

                    <hr>

                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-between">
                        <?= Html::a('Cancelar', ['index'], ['class' => 'btn']) ?>
                        <?= Html::submitButton('Guardar Alterações', ['class' => 'btn']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </div>
</div>

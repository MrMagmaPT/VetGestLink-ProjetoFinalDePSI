<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\SignupForm $model */
/** @var common\models\Userprofile $userprofile */
/** @var bool $isUpdate */

$isUpdate = isset($isUpdate) && $isUpdate === true;
?>

<div class="userprofile-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <!-- Dados de Acesso -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Dados de Acesso</h3>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?php if (!$isUpdate): ?>
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Informações Pessoais -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Informações Pessoais</h3>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'nomecompleto')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'dtanascimento')->input('date', [
                            'max' => date('Y-m-d')
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'nif')->textInput(['maxlength' => 9]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'telemovel')->textInput(['maxlength' => 9]) ?>
                </div>
            </div>

            <?php if (!$isUpdate): ?>
                <?= $form->field($model, 'imageFile')->fileInput() ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Morada -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Morada</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'rua')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'nporta')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'andar')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'cdpostal')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'cxpostal')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'localidade')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($isUpdate ? 'Atualizar' : 'Registar', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancelar', ['view'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

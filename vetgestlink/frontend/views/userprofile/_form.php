<?php

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\Morada[] $moradas */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<div class="userprofile-form">

    <?php $form = ActiveForm::begin([
        'action' => ['userprofile/save'],
        'method' => 'post'
    ]); ?>

    <!-- PROFILE -->
    <h5 class="section-title">Informações pessoais</h5>

    <?= $form->field($model, 'nomecompleto')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'telemovel')->textInput(['maxlength' => true]) ?>

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
        <?= Html::a('Cancelar', ['userprofile/view'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::submitButton('Guardar Alterações', ['class' => 'btn btn-accent']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


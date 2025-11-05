<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="marcacoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'horainicio')->textInput()->label('Hora Inicio') ?>

    <?= $form->field($model, 'horafim')->textInput()->label('Hora fim') ?>

    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false); ?>

    <?= $form->field($model, 'diagnostico')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'pendente' => 'Pendente', 'cancelada' => 'Cancelada', 'realizada' => 'Realizada', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'tipo')->dropDownList([ 'consulta' => 'Consulta', 'cirurgia' => 'Cirurgia', 'operacao' => 'Operacao', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'animais_id')->textInput() ?>

    <?= $form->field($model, 'userprofiles_id')->textInput() ?>

    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

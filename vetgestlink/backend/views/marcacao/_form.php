<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="marcacoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'created_at')->Input('int')->label(false) ?>

    <?= $form->field($model, 'updated_at')->Input('int')->label(false); ?>

    <!-- Date input using HTML5 'date' type -->
    <?= $form->field($model, 'data')->input('date') ?>

    <!-- Time input using HTML5 'time' type -->
    <?= $form->field($model, 'horainicio')->input('time')->label('Hora Inicio') ?>

    <?= $form->field($model, 'horafim')->input('time')->label('Hora fim') ?>

    <?= $form->field($model, 'diagnostico')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'estado')->dropDownList([
        'pendente' => 'Pendente',
        'cancelada' => 'Cancelada',
        'realizada' => 'Realizada',
    ], ['prompt' => '']) ?>

    <?= $form->field($model, 'tipo')->dropDownList([
        'consulta' => 'Consulta',
        'cirurgia' => 'Cirurgia',
        'operacao' => 'Operacao',
    ], ['prompt' => '']) ?>

    <?php
    $tipo_marcacao = $form->field($model, 'tipo');
    if ($tipo_marcacao == 'cirurgia') {
        $form->field($model, 'preco')->hiddenInput(['value' => 200])->label(false);
    } else if ($tipo_marcacao == 'consulta') {
        $form->field($model, 'preco')->hiddenInput(['value' => 30])->label(false);

    } else if ($tipo_marcacao == 'operacao') {
        $form->field($model, 'preco')->hiddenInput(['value' => 100])->label(false);

    }
    ?>

    <?= $form->field($model, 'animais_id')->input('number') ?>

    <?= $form->field($model, 'userprofiles_id')->input('number') ?>

    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
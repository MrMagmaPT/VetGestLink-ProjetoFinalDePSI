<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\LinhafaturaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="linhafatura-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'quantidade') ?>

    <?= $form->field($model, 'vendidoemconsulta') ?>

    <?= $form->field($model, 'faturas_id') ?>

    <?php // echo $form->field($model, 'servicos_id') ?>

    <?php // echo $form->field($model, 'medicamentos_id') ?>

    <?php // echo $form->field($model, 'marcacoes_id') ?>

    <?php // echo $form->field($model, 'eliminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

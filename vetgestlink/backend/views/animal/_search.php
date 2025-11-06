<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AnimalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="animais-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'dtanascimento') ?>


    <?= $form->field($model, 'peso') ?>

    //TODO : ajustar conforme a nosssa necessidade
    <?php // echo $form->field($model, 'microship') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'especies_id') ?>

    <?php // echo $form->field($model, 'userprofiles_id') ?>

    <?php // echo $form->field($model, 'racas_id') ?>

    <?php // echo $form->field($model, 'eliminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

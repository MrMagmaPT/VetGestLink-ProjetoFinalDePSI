<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faturas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faturas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'metodospagamentos_id')->textInput() ?>

    <?= $form->field($model, 'userprofiles_id')->textInput() ?>

    <?= $form->field($model, 'eliminado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

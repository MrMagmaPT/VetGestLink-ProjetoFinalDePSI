<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faturas-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'errorOptions' => ['class' => 'text-danger fw-bold', 'style' => 'color: #dc3545 !important; font-size: 0.875rem; margin-top: 0.25rem;'],
        ],
    ]); ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'metodospagamentos_id')->textInput() ?>

    <?= $form->field($model, 'userprofiles_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

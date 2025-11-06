<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Nova Nota';
?>

<div class="nota-create container py-4">

    <h3 class="mb-3">Adicionar Nota</h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nota')->textarea(['rows' => 4]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-accent']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

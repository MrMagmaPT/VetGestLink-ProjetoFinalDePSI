<?php

/** @var yii\web\View $this */
/** @var common\models\Nota $model */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<div class="nota-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nota')->textarea(['rows' => 6, 'placeholder' => 'Escreva aqui a nota sobre o animal...']) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-accent']) ?>
        <?= Html::a('Cancelar', ['animal/view', 'id' => $model->animais_id], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


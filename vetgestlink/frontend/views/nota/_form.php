<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */
?>

<div class="nota-update-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nota')->textarea([
        'rows' => 5,
        'placeholder' => 'Atualize a nota aqui...'
    ]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Atualizar Nota', ['class' => 'btn btn-dark rounded-pill']) ?>
        <?= Html::a('Cancelar', ['nota/index', 'animal_id' => $model->animais_id], ['class' => 'btn btn-dark rounded-pill']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

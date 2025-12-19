<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */
?>
<div class="nota-form d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="card shadow-sm w-100" style="max-width: 500px;">
        <div class="card-header bg-white text-center">
            <h5 class="mb-0">
                <i class="fa-regular fa-sticky-note text-primary"></i>
                <?= $model->isNewRecord ? 'Criar Nota' : 'Atualizar Nota' ?>
            </h5>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'nota')->textarea([
                'rows' => 5,
                'placeholder' => 'Digite sua nota aqui...'
            ])->label('Nota') ?>

            <div class="form-group mt-3 d-flex justify-content-between">
                <?= Html::submitButton($model->isNewRecord ? 'Criar Nota' : 'Atualizar Nota', ['class' => 'btn btn-primary rounded-pill']) ?>
                <?= Html::a('Cancelar', ['index', 'animalId' => $model->animais_id], ['class' => 'btn btn-secondary rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

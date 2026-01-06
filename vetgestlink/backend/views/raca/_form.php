<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Especie;

/** @var yii\web\View $this */
/** @var common\models\Raca $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="raca-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'errorOptions' => ['class' => 'text-danger fw-bold', 'style' => 'color: #dc3545 !important; font-size: 0.875rem; margin-top: 0.25rem;'],
        ],
    ]); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-dog text-primary"></i>
                        Dados da Raça
                    </h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'nome')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Nome da raça (ex: Labrador, Poodle, Pastor Alemão)'
                    ]) ?>

                    <?= $form->field($model, 'especies_id')->dropDownList(
                        $especiesAtivas,
                        ['prompt' => 'Selecione uma espécie', 'class' => 'form-control']
                    )->label('Espécie') ?>

                    <?php if ($model->isNewRecord): ?>
                        <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>
                    <?php else: ?>
                        <?= $form->field($model, 'eliminado')->checkbox([
                            'label' => 'Marcar como eliminado',
                            'uncheck' => 0
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info"></i>
                        Ajuda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-lightbulb"></i>
                            Exemplos de raças: Labrador, Poodle, Pastor Alemão, Siamês, Persa, etc.<br>
                            <i class="fas fa-paw"></i>
                            Escolha a espécie correta para a raça.
                        </small>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks text-secondary"></i>
                        Ações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save"></i> Guardar',
                            ['class' => 'btn btn-success btn-md']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-times"></i> Cancelar',
                            ['index'],
                            ['class' => 'btn btn-secondary btn-md']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Especie $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'options' => ['autocomplete' => 'off'],
                    'fieldConfig' => [
                        'errorOptions' => ['class' => 'text-danger fw-bold', 'style' => 'color: #dc3545 !important; font-size: 0.875rem; margin-top: 0.25rem;'],
                    ],
                ]); ?>

                <div class="row">
                    <div class="col-md-8">
                        <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    </div>
                        <?php if ($model->isNewRecord): ?>
                            <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>
                        <?php else: ?>
                            <?= $form->field($model, 'eliminado')->checkbox([
                                'label' => 'Marcar como eliminado',
                                'uncheck' => 0
                            ]) ?>
                        <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <?= Html::submitButton(
                        $model->isNewRecord ? '<i class="fas fa-save"></i> Criar' : '<i class="fas fa-save"></i> Atualizar',
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm border-info">
            <div class="card-header bg-info text-white">
                <i class="fas fa-info-circle"></i> Ajuda
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="fas fa-paw"></i> O campo <strong>Nome</strong> é obrigatório.</li>
                    <li><i class="fas fa-check"></i> Estado <strong>Ativa</strong> indica espécie disponível.</li>
                    <li><i class="fas fa-times"></i> Estado <strong>Eliminada</strong> oculta a espécie do sistema.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

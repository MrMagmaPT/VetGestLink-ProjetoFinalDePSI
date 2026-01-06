<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Servico $model */
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
                    <div class="col-md-4">
                        <?= $form->field($model, 'valor')->textInput([
                            'type' => 'number',
                            'step' => '0.01',
                            'min' => '0',
                            'class' => 'form-control'
                        ])->label('Valor (€)') ?>
                    </div>
                </div>

                <?php if (!$model->isNewRecord): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'eliminado')->dropDownList([
                                0 => 'Ativo',
                                1 => 'Eliminado',
                            ], ['class' => 'form-control']) ?>
                        </div>
                    </div>
                <?php endif; ?>
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
                    <li><i class="fas fa-concierge-bell"></i> O campo <strong>Nome</strong> é obrigatório.</li>
                    <li><i class="fas fa-euro-sign"></i> O <strong>Valor</strong> deve ser um número positivo.</li>
                    <li><i class="fas fa-check"></i> Estado <strong>Ativo</strong> indica serviço disponível.</li>
                    <li><i class="fas fa-times"></i> Estado <strong>Eliminado</strong> oculta o serviço do sistema.</li>
                </ul>
            </div>
        </div>
        <!-- Card Ações -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-cog text-secondary"></i>
                    Ações
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?= Html::submitButton(
                        '<i class="fas fa-save me-2"></i>' . ($model->isNewRecord ? 'Criar Serviço' : 'Guardar Alterações'),
                        ['class' => 'btn btn-success btn-md']
                    ) ?>
                    <?= Html::a(
                        '<i class="fas fa-times me-2"></i>Cancelar',
                        ['index'],
                        ['class' => 'btn btn-secondary btn-md']
                    ) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

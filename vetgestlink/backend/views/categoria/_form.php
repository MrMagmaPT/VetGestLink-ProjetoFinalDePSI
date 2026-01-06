<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Categoria $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categorias-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'needs-validation'],
        'fieldConfig' => [
            'errorOptions' => ['class' => 'text-danger fw-bold', 'style' => 'color: #dc3545 !important; font-size: 0.875rem; margin-top: 0.25rem;'],
        ],
    ]); ?>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-md-8">
            <!-- Card Informações da Categoria -->
            <div class="card shadow-sm mb-4 hover-shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-folder text-primary me-2"></i>
                        Informações da Categoria
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'nome')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Ex: Antibióticos, Analgésicos, Antiparasitários...',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-tag me-2"></i> Nome da Categoria') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita -->
        <div class="col-md-4">
            <!-- Card Informação -->
            <div class="card shadow-sm mb-4 hover-shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Informação
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0 small">
                        <i class="fas fa-info-circle"></i>
                        <strong>Dica:</strong><br>
                        Use nomes descritivos para facilitar a organização dos medicamentos.<br><br>
                        <strong>Exemplos:</strong><br>
                        • Antibióticos<br>
                        • Analgésicos<br>
                        • Antiparasitários<br>
                        • Vacinas<br>
                        • Vitaminas
                    </div>
                </div>
            </div>

            <!-- Card Ações -->
            <div class="card shadow-sm hover-shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs text-secondary me-2"></i>
                        Ações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save me-2"></i>' . ($model->isNewRecord ? 'Criar Categoria' : 'Guardar Alterações'),
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
        </div>
    </div>

    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>

    <?php ActiveForm::end(); ?>
</div>


<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Categoria;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="medicamentos-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'needs-validation']
    ]); ?>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-md-8">
            <!-- Card Informações do Medicamento -->
            <div class="card shadow-sm mb-4 hover-shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-pills text-primary me-2"></i>
                        Informações do Medicamento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'nome')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Ex: Paracetamol 500mg',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-capsules me-2"></i> Nome do Medicamento') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'descricao')->textarea([
                                'rows' => 4,
                                'maxlength' => true,
                                'placeholder' => 'Descreva o medicamento, indicações, posologia...',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-file-medical me-2"></i> Descrição') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'preco')->input('number', [
                                'step' => '0.01',
                                'min' => '0',
                                'placeholder' => '0.00',
                                'class' => 'form-control text-end'
                            ])->label('<i class="fas fa-euro-sign me-2"></i> Preço (€)') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'quantidade')->input('number', [
                                'min' => '0',
                                'placeholder' => '0',
                                'class' => 'form-control text-end'
                            ])->label('<i class="fas fa-boxes me-2"></i> Quantidade em Stock') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'categorias_id')->dropDownList(
                                ArrayHelper::map(Categoria::find()->where(['eliminado' => 0])->all(), 'id', 'nome'),
                                [
                                    'prompt' => 'Selecione uma categoria',
                                    'class' => 'form-control'
                                ]
                            )->label('<i class="fas fa-folder me-2"></i> Categoria') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita -->
        <div class="col-md-4">
            <!-- Card Informações de Stock -->
            <div class="card shadow-sm mb-4 hover-shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-warehouse text-info me-2"></i>
                        Níveis de Stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0 small">
                        <i class="fas fa-info-circle"></i>
                        <strong>Níveis:</strong><br>
                        <span class="badge bg-success d-inline-block my-1">≥ 10</span> Stock Bom<br>
                        <span class="badge bg-warning d-inline-block my-1">5 - 9</span> Stock Baixo<br>
                        <span class="badge bg-danger d-inline-block my-1">< 5</span> Stock Crítico
                    </div>
                </div>
            </div>

            <!-- Card Ações -->
            <div class="card shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save me-2"></i>' . ($model->isNewRecord ? 'Criar Medicamento' : 'Guardar Alterações'),
                            ['class' => 'btn btn-success btn-lg']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-times me-2"></i>Cancelar',
                            ['index'],
                            ['class' => 'btn btn-secondary btn-lg']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>

    <?php ActiveForm::end(); ?>
</div>

<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transition: box-shadow 0.3s ease;
}
</style>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Metodopagamento $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="metodopagamento-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'needs-validation']
    ]); ?>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-md-8">
            <!-- Card Informações do Método de Pagamento -->
            <div class="card shadow-sm mb-4 hover-shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        Informações do Método de Pagamento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'nome')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Ex: Dinheiro, Multibanco, MB Way, Cartão de Crédito...',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-tag me-2"></i> Nome do Método') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'vigor')->dropDownList([
                                1 => 'Ativo',
                                0 => 'Inativo',
                            ], [
                                'class' => 'form-control',
                                'options' => [1 => ['selected' => true]]
                            ])->label('<i class="fas fa-toggle-on me-2"></i> Estado de Vigor') ?>
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
                        <strong>Sobre métodos de pagamento:</strong><br>
                        Configure os métodos disponíveis para pagamento de faturas.<br><br>
                        <strong>Exemplos:</strong><br>
                        • Dinheiro<br>
                        • Multibanco<br>
                        • MB Way<br>
                        • Cartão de Crédito<br>
                        • Transferência Bancária
                    </div>
                </div>
            </div>

            <!-- Card Ações -->
            <div class="card shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save me-2"></i>' . ($model->isNewRecord ? 'Criar Método' : 'Guardar Alterações'),
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

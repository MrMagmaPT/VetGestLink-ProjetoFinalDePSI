<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $metodosPagamento */
/** @var array $userprofilesList */
?>

<div class="faturas-form">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'needs-validation']]); ?>

    <div class="row">
        <!-- Coluna Esquerda - Formulário -->
        <div class="col-md-8">
            <!-- Card Informações da Fatura -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar text-primary"></i>
                        Informações da Fatura
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'total')->textInput([
                                'type' => 'number',
                                'step' => '0.01',
                                'min' => '0',
                                'placeholder' => 'Ex: 150.00',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-euro-sign me-2"></i> Total (€)') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'created_at')->textInput([
                                'type' => 'datetime-local',
                                'class' => 'form-control',
                                'value' => $model->created_at ? date('Y-m-d\TH:i', strtotime($model->created_at)) : date('Y-m-d\TH:i')
                            ])->label('<i class="far fa-calendar-alt me-2"></i> Data e Hora') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'estado')->dropDownList(
                                [0 => 'Pendente', 1 => 'Paga'],
                                [
                                    'prompt' => 'Selecione o estado...',
                                    'class' => 'form-control'
                                ]
                            )->label('<i class="fas fa-info-circle me-2"></i> Estado') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'metodospagamentos_id')->widget(Select2::classname(), [
                                'data' => $metodosPagamento,
                                'options' => ['placeholder' => 'Selecione o método de pagamento...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('<i class="fas fa-credit-card me-2"></i> Método de Pagamento') ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'userprofiles_id')->widget(Select2::classname(), [
                        'data' => $userprofilesList,
                        'options' => ['placeholder' => 'Selecione o cliente...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('<i class="fas fa-user me-2"></i> Cliente') ?>
                </div>
            </div>
        </div>

        <!-- Coluna Direita - Informação e Ações -->
        <div class="col-md-4">
            <!-- Card Informação -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info"></i>
                        Informação
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0 small">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Dica:</strong><br>
                        Preencha todos os campos obrigatórios para criar a fatura.<br><br>
                        <strong>Estado:</strong><br>
                        • <span class="badge bg-warning">Pendente</span> - Aguarda pagamento<br>
                        • <span class="badge bg-success">Paga</span> - Pagamento confirmado
                    </div>
                </div>
            </div>

            <!-- Card Ações -->
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
                            '<i class="fas fa-save"></i> ' . ($model->isNewRecord ? 'Criar Fatura' : 'Guardar Alterações'),
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

    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>

    <?php ActiveForm::end(); ?>
</div>

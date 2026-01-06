<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Lembrete $lembrete */
/** @var yii\widgets\ActiveForm $form */

?>
<div class="lembrete-form py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient bg-warning text-dark text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-bell me-2"></i>
                            <?= $lembrete->isNewRecord ? 'Criar Lembrete' : 'Atualizar Lembrete' ?>
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'errorOptions' => ['class' => 'invalid-feedback d-block'],
                            ],
                        ]); ?>

                        <div class="mb-4">
                            <?= $form->field($lembrete, 'descricao')->textarea([
                                'rows' => 6,
                                'class' => 'form-control',
                                'placeholder' => 'Digite o lembrete aqui...'
                            ])->label('<i class="fas fa-pen me-2"></i>Descrição do Lembrete', ['class' => 'form-label fw-bold']) ?>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                            <?= Html::a(
                                '<i class="fas fa-times me-2"></i>Cancelar',
                                ['index'],
                                ['class' => 'btn btn-outline-secondary']
                            ) ?>
                            <?= Html::submitButton(
                                ($lembrete->isNewRecord ? '<i class="fas fa-save me-2"></i>Criar Lembrete' : '<i class="fas fa-check me-2"></i>Atualizar Lembrete'),
                                ['class' => 'btn btn-warning ']
                            ) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                
                <!-- Dica -->
                <div class="alert alert-warning mt-3 shadow-sm border-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    <small><strong>Dica:</strong> Use lembretes para não esquecer de tarefas importantes.</small>
                </div>
            </div>
        </div>
    </div>
</div>



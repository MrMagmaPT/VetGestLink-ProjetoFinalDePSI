<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */
?>
<div class="nota-form py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient bg-primary text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-sticky-note me-2"></i>
                            <?= $model->isNewRecord ? 'Criar Nota' : 'Atualizar Nota' ?>
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'errorOptions' => ['class' => 'invalid-feedback d-block'],
                            ],
                        ]); ?>

                        <div class="mb-4">
                            <?= $form->field($model, 'nota')->textarea([
                                'rows' => 8,
                                'class' => 'form-control',
                                'placeholder' => 'Digite o conteúdo da nota aqui...'
                            ])->label('<i class="fas fa-pen me-2"></i>Conteúdo da Nota', ['class' => 'form-label fw-bold']) ?>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                            <?= Html::a(
                                '<i class="fas fa-times me-2"></i>Cancelar',
                                ['index', 'animalId' => $model->animais_id],
                                ['class' => 'btn btn-outline-secondary']
                            ) ?>
                            <?= Html::submitButton(
                                ($model->isNewRecord ? '<i class="fas fa-save me-2"></i>Criar Nota' : '<i class="fas fa-check me-2"></i>Atualizar Nota'),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                
                <!-- Dica -->
                <div class="alert alert-info mt-3 shadow-sm border-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    <small><strong>Dica:</strong> Use notas para registrar observações importantes sobre o animal.</small>
                </div>
            </div>
        </div>
    </div>
</div>

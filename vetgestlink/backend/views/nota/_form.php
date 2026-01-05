<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="nota-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'needs-validation']
    ]); ?>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-md-8">
            <!-- Card Informações da Nota -->
            <div class="card shadow-sm mb-4 hover-shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note text-primary me-2"></i>
                        Informações da Nota
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'nota')->textarea([
                                'rows' => 6,
                                'maxlength' => true,
                                'placeholder' => 'Escreva aqui a nota ou observação sobre o animal...',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-comment-medical me-2"></i> Nota / Observação') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'animais_id')->hiddenInput(['value' => $model->animais_id])->label(false) ?>

                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'userprofiles_id')->hiddenInput(['value' => $model->userprofiles_id])->label(false) ?>

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
                        <i class="fas fa-lightbulb"></i>
                        <strong>Sobre notas:</strong><br>
                        As notas permitem registar observações importantes sobre os animais.<br><br>
                        <strong>Dicas:</strong><br>
                        • Use notas para detalhar comportamento<br>
                        • Registe alergias conhecidas<br>
                        • Anote preferências alimentares<br>
                        • Documente histórico veterinario
                    </div>
                </div>
            </div>

            <!-- Card Ações -->
            <div class="card shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save me-2"></i>' . ($model->isNewRecord ? 'Criar Nota' : 'Guardar Alterações'),
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

    <?php ActiveForm::end(); ?>
</div>

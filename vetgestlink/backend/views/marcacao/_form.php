<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Animal;
use common\models\Userprofile;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="marcacoes-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'needs-validation']
    ]); ?>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-md-8">
            <!-- Card Informações da Marcação -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        Informações da Marcação
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'data')->input('date', [
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-calendar"></i> Data') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'horainicio')->input('time', [
                                'class' => 'form-control'
                            ])->label('<i class="far fa-clock"></i> Hora Início') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'horafim')->input('time', [
                                'class' => 'form-control'
                            ])->label('<i class="far fa-clock"></i> Hora Fim') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'animais_id')->dropDownList(
                                ArrayHelper::map(Animal::find()->where(['eliminado' => 0])->all(), 'id', 'nome'),
                                [
                                    'prompt' => 'Selecione um animal',
                                    'class' => 'form-control'
                                ]
                            )->label('<i class="fas fa-paw"></i> Animal') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'userprofiles_id')->dropDownList(
                                ArrayHelper::map(Userprofile::find()->where(['eliminado' => 0])->all(), 'id', 'nomecompleto'),
                                [
                                    'prompt' => 'Selecione um veterinário',
                                    'class' => 'form-control'
                                ]
                            )->label('<i class="fas fa-user-md"></i> Veterinário') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'diagnostico')->textarea([
                                'rows' => 4,
                                'placeholder' => 'Descreva o diagnóstico ou observações...',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-stethoscope"></i> Diagnóstico / Observações') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita -->
        <div class="col-md-4">
            <!-- Card Estado -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info"></i>
                        Estado da Marcação
                    </h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'estado')->dropDownList([
                        'pendente' => 'Pendente',
                        'cancelada' => 'Cancelada',
                        'realizada' => 'Realizada',
                    ], [
                        'prompt' => 'Selecione o estado',
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-check-circle"></i> Estado') ?>

                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            <strong>Estados:</strong><br>
                            <span class="badge bg-warning">Pendente</span> - Aguardando realização<br>
                            <span class="badge bg-success">Realizada</span> - Marcação concluída<br>
                            <span class="badge bg-danger">Cancelada</span> - Marcação cancelada
                        </small>
                    </div>
                </div>
            </div>

            <!-- Card Ações -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save"></i> ' . ($model->isNewRecord ? 'Criar Marcação' : 'Guardar Alterações'),
                            ['class' => 'btn btn-success btn-lg']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-times"></i> Cancelar',
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
.marcacoes-form .card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transition: box-shadow 0.3s ease;
}

.marcacoes-form .card-header h5 i {
    margin-right: 0.5rem;
}

.marcacoes-form .form-group label i {
    margin-right: 0.375rem;
    width: 1.125rem;
    text-align: center;
}

.marcacoes-form .btn i {
    margin-right: 0.5rem;
}

.marcacoes-form .alert {
    font-size: 0.875rem;
}

.marcacoes-form .alert .badge {
    display: inline-block;
    margin: 0.25rem 0;
}
</style>
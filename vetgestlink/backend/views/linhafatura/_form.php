<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var common\models\Linhafatura $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $medicamentosList */
/** @var array $servicosList */
/** @var array $marcacoesList */

$this->registerJs("
$('#linhafatura-medicamentos_id').on('change', function() {
    if ($(this).val()) {
        $('#linhafatura-servicos_id').val(null).trigger('change');
    }
});

$('#linhafatura-servicos_id').on('change', function() {
    if ($(this).val()) {
        $('#linhafatura-medicamentos_id').val(null).trigger('change');
    }
});
");
?>

<div class="linhafatura-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <?php if ($model->isNewRecord): ?>
                            <i class="fas fa-plus-circle text-primary"></i>
                            Adicionar Linha à Fatura
                        <?php else: ?>
                            <i class="fas fa-edit text-warning"></i>
                            Editar Linha da Fatura
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    
                    <?= $form->field($model, 'faturas_id')->hiddenInput()->label(false) ?>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Atenção:</strong> Selecione um medicamento OU um serviço, não ambos.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'medicamentos_id')->widget(Select2::class, [
                                'data' => $medicamentosList,
                                'options' => ['placeholder' => 'Selecione um medicamento...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Medicamento') ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'servicos_id')->widget(Select2::class, [
                                'data' => $servicosList,
                                'options' => ['placeholder' => 'Selecione um serviço...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Serviço') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'quantidade')->textInput([
                                'type' => 'number',
                                'min' => 1,
                                'value' => $model->isNewRecord ? 1 : $model->quantidade
                            ]) ?>
                        </div>

                        <div class="col-md-6">
                            <div class="alert alert-secondary mt-4">
                                <i class="fas fa-calculator"></i>
                                O total será calculado automaticamente
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <?= Html::a(
                            '<i class="fas fa-arrow-left"></i> Voltar',
                            ['/fatura/view', 'id' => $model->faturas_id],
                            ['class' => 'btn btn-secondary']
                        ) ?>
                        <?php if ($model->isNewRecord): ?>
                            <?= Html::submitButton(
                                '<i class="fas fa-save"></i> Adicionar Linha',
                                ['class' => 'btn btn-success']
                            ) ?>
                        <?php else: ?>
                            <?= Html::submitButton(
                                '<i class="fas fa-save"></i> Atualizar Linha',
                                ['class' => 'btn btn-warning']
                            ) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Card com listas de itens disponíveis -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-pills text-info"></i>
                        Medicamentos Disponíveis
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <?php if (empty($medicamentosList)): ?>
                        <p class="text-muted text-center"><i>Nenhum medicamento disponível</i></p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($medicamentosList as $id => $nome): ?>
                                <li class="mb-2">
                                    <i class="fas fa-circle text-success" style="font-size: 6px;"></i>
                                    <small><?= Html::encode($nome) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-briefcase-medical text-primary"></i>
                        Serviços Disponíveis
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <?php if (empty($servicosList)): ?>
                        <p class="text-muted text-center"><i>Nenhum serviço disponível</i></p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($servicosList as $id => $nome): ?>
                                <li class="mb-2">
                                    <i class="fas fa-circle text-primary" style="font-size: 6px;"></i>
                                    <small><?= Html::encode($nome) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

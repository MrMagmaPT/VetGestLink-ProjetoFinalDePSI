<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Animal;
use common\models\Userprofile;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $animaisList */
/** @var array $veterinariosArray */
/** @var array $medicamentos */
/** @var array $medicamentosAtuaisMap */
/** @var array $servicosList */
?>

<div class="marcacoes-form">
    <?php $form = ActiveForm::begin([
        'errorCssClass' => 'has-error',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n<div class=\"text-danger\">{error}</div>",
        ],
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
                                'class' => 'form-control',
                                'required' => true
                            ])->label('<i class="fas fa-calendar me-2"></i> Data') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'horainicio')->input('time', [
                                'class' => 'form-control',
                                'required' => true
                            ])->label('<i class="far fa-clock me-2"></i> Hora Início') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'horafim')->input('time', [
                                'class' => 'form-control',
                                'required' => true
                            ])->label('<i class="far fa-clock me-2"></i> Hora Fim') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'animais_id')->dropDownList(
                                $animaisList,
                                [
                                    'prompt' => 'Selecione um animal',
                                    'class' => 'form-control',
                                    'required' => true
                                ]
                            )->label('<i class="fas fa-paw me-2"></i> Animal') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'userprofiles_id')->dropDownList(
                                $veterinariosArray,
                                [
                                    'prompt' => 'Selecione um veterinário',
                                    'class' => 'form-control',
                                    'required' => true
                                ]
                            )->label('<i class="fas fa-user-md me-2"></i> Veterinário') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'servicos_id')->dropDownList(
                                $servicosList,
                                [
                                    'prompt' => 'Selecione um serviço',
                                    'class' => 'form-control',
                                    'required' => true
                                ]
                            )->label('<i class="fas fa-briefcase-medical me-2"></i> Serviço') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'diagnostico')->textarea([
                                'rows' => 4,
                                'placeholder' => 'Descreva o diagnóstico ou observações...',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-stethoscope me-2"></i> Diagnóstico / Observações') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Medicamentos -->
            <div class="card shadow-sm mb-4" id="card-medicamentos" style="<?= !$model->isNewRecord ? '' : 'display: none;' ?>">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-pills text-success"></i>
                        Medicamentos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> Os medicamentos só podem ser adicionados quando a marcação está no estado <strong>"realizada"</strong>.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                            <tr>
                                <th>Medicamento</th>
                                <th style="width: 150px;">Quantidade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $medicamentosAtuaisMap = $medicamentosAtuaisMap ?? [];
                            foreach ($medicamentos as $medicamento): 
                                $quantidadeAtual = isset($medicamentosAtuaisMap[$medicamento->id]) ? $medicamentosAtuaisMap[$medicamento->id]['quantidade'] : 0;
                            ?>
                                <tr>
                                    <td>
                                        <strong><?= Html::encode($medicamento->nome) ?></strong>
                                        <?php if ($medicamento->descricao): ?>
                                            <br><small class="text-muted"><?= Html::encode($medicamento->descricao) ?></small>
                                        <?php endif; ?>
                                        <br><small class="text-muted">Stock disponível: <?= $medicamento->quantidade + $quantidadeAtual ?></small>
                                    </td>
                                    <td>
                                        <input type="number"
                                               class="form-control form-control-sm medicamento-quantidade"
                                               name="medicamentos[<?= $medicamento->id ?>][quantidade]"
                                               min="0"
                                               max="<?= $medicamento->quantidade + $quantidadeAtual ?>"
                                               placeholder="0"
                                               value="<?= $quantidadeAtual ?>"
                                               data-medicamento-id="<?= $medicamento->id ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
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
                    <?php if ($model->isNewRecord): ?>
                        <!-- Modo Criação - Estado sempre Pendente -->
                        <?= $form->field($model, 'estado')->hiddenInput(['value' => 'pendente'])->label(false) ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Estado: Pendente</strong>
                        </div>
                    <?php else: ?>
                        <!-- Modo Edição - Permite alterar estado -->
                        <?= $form->field($model, 'estado')->dropDownList(
                            \common\models\Marcacao::optsEstado(),
                            [
                                'class' => 'form-control',
                                'id' => 'marcacao-estado',
                                'prompt' => 'Selecione o estado'
                            ]
                        )->label('<i class="fas fa-info-circle me-2"></i> Estado') ?>
                    <?php endif; ?>

                    <div class="alert alert-info mt-3 small">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Estados:</strong><br>
                        <span class="badge bg-warning d-inline-block my-1">Pendente</span> - Aguardando realização<br>
                        <span class="badge bg-success d-inline-block my-1">Realizada</span> - Marcação concluída<br>
                        <span class="badge bg-danger d-inline-block my-1">Cancelada</span> - Marcação cancelada
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
                            '<i class="fas fa-save me-2"></i>' . ($model->isNewRecord ? 'Criar Marcação' : 'Guardar Alterações'),
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
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */

$this->title = "Fatura #" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$isPaid = (bool)$model->estado;
$estadoText = $isPaid ? 'Pago' : 'Pendente';
$estadoColor = $isPaid ? 'success' : 'warning';
$estadoIcon = $isPaid ? 'check-circle' : 'clock';
?>

<div class="faturas-view">
    <div class="container py-4">

        <!-- Header com Título e Botões -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-file-invoice-dollar text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h2>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
                <?php if (!$isPaid): ?>
                    <?= Html::a(
                        '<i class="fas fa-money-bill-wave"></i> Pagar Agora',
                        ['pagar', 'id' => $model->id],
                        [
                            'class' => 'btn btn-success',
                            'data-method' => 'post',
                            'data-confirm' => 'Confirma o pagamento desta fatura?'
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <!-- Coluna Esquerda: Informações da Fatura -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-<?= $estadoColor ?> text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i>
                            Informações da Fatura
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Total em Destaque -->
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">Total</small>
                            <h2 class="text-<?= $estadoColor ?> mb-0">
                                <?= Yii::$app->formatter->asCurrency($model->total, 'EUR') ?>
                            </h2>
                        </div>

                        <!-- Detalhes -->
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-borderless mb-0'],
                            'attributes' => [
                                [
                                    'label' => 'Cliente',
                                    'format' => 'raw',
                                    'value' => '<strong><i class="fas fa-user"></i> ' . 
                                               Html::encode($model->userprofiles->nomecompleto ?? "N/A") . 
                                               '</strong>',
                                ],
                                [
                                    'label' => 'Data',
                                    'format' => 'raw',
                                    'value' => '<i class="far fa-calendar"></i> ' . 
                                               Yii::$app->formatter->asDate($model->created_at, 'php:d/m/Y'),
                                ],
                                [
                                    'label' => 'Estado',
                                    'format' => 'raw',
                                    'value' => '<span class="badge bg-' . $estadoColor . '">' .
                                               '<i class="fas fa-' . $estadoIcon . '"></i> ' .
                                               $estadoText . '</span>',
                                ],
                            ],
                        ]) ?>

                        <?php if ($isPaid && $model->metodospagamentos): ?>
                            <div class="alert alert-success mt-3 mb-0">
                                <strong><i class="fas fa-credit-card"></i> Método de Pagamento:</strong><br>
                                <?= Html::encode($model->metodospagamentos->nome) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Linhas da Fatura -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i>
                            Itens da Fatura
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($model->linhasfaturas)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="fas fa-box"></i> Item</th>
                                            <th class="text-center"><i class="fas fa-sort-numeric-up"></i> Quantidade</th>
                                            <th class="text-end"><i class="fas fa-euro-sign"></i> Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($model->linhasfaturas as $linha): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if ($linha->medicamentos_id) {
                                                        echo '<i class="fas fa-pills text-primary"></i> ' . 
                                                             Html::encode($linha->medicamentos->nome);
                                                    } elseif ($linha->marcacoes_id) {
                                                        echo '<i class="fas fa-calendar-check text-success"></i> Consulta - ' . 
                                                             Html::encode($linha->marcacoes->descricao ?? "Sem descrição");
                                                    } elseif ($linha->servicos_id) {
                                                        echo '<i class="fas fa-concierge-bell text-info"></i> ' . 
                                                             Html::encode($linha->servicos->nome ?? "Serviço");
                                                    } else {
                                                        echo '<i class="fas fa-question-circle text-muted"></i> N/A';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary"><?= $linha->quantidade ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <strong><?= Yii::$app->formatter->asCurrency($linha->total, 'EUR') ?></strong>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="2" class="text-end">Total Geral:</th>
                                            <th class="text-end">
                                                <h5 class="text-<?= $estadoColor ?> mb-0">
                                                    <?= Yii::$app->formatter->asCurrency($model->total, 'EUR') ?>
                                                </h5>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info m-3">
                                <i class="fas fa-info-circle"></i>
                                Nenhum item encontrado nesta fatura.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


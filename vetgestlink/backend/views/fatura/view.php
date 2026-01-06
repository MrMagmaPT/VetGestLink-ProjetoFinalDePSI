<?php

use yii\helpers\Html;
use backend\widgets\SmallCardWidget;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */

$this->title = "Fatura de {$model->userprofiles->nomecompleto}";
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-file-invoice text-primary"></i>
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Faturas', ['index']) ?></li>
                    <li class="breadcrumb-item active">Visualizar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Resumo -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => 'fa-euro-sign',
                'iconColorClass' => 'icon-success',
                'text' => 'Total',
                'value' => number_format($model->total, 2, ',', '.') . ' €',
                'url' => '#',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-info-circle',
                'iconColorClass' => $model->estado == 1 ? 'icon-success' : 'icon-warning',
                'text' => 'Estado',
                'value' => $model->estado == 1 ? 'Paga' : 'Pendente',
                'url' => '#',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-credit-card',
                'iconColorClass' => $model->metodospagamentos ? 'icon-blue' : 'icon-secondary',
                'text' => 'Método Pagamento',
                'value' => $model->metodospagamentos ? $model->metodospagamentos->nome : 'Não definido',
                'url' => '#',
            ]) ?>

            <?= SmallCardWidget::widget([
                'icon' => 'fa-list',
                'iconColorClass' => 'icon-purple',
                'text' => 'Linhas',
                'value' => count($model->linhasfaturas),
                'url' => '#',
            ]) ?>
        </div>

        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Card Informações da Fatura -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-file-invoice text-primary"></i>
                            Informações da Fatura
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- ID da Fatura -->
                        <!-- <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-hashtag"></i> ID:
                            </div>
                            <div class="col-md-9">
                                <strong><?= Html::encode($model->id) ?></strong>
                            </div>
                        </div> -->
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-user"></i> Cliente:
                            </div>
                            <div class="col-md-9">
                                <?= $model->userprofiles ? Html::a(
                                    Html::encode($model->userprofiles->nomecompleto),
                                    ['/userprofile/view', 'id' => $model->userprofiles->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-calendar"></i> Data:
                            </div>
                            <div class="col-md-9">
                                <?= $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i') : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-info-circle"></i> Estado:
                            </div>
                            <div class="col-md-9">
                                <?php
                                $estadoClass = $model->estado == 1 ? 'bg-success' : 'bg-warning';
                                $estadoTexto = $model->estado == 1 ? 'Paga' : 'Pendente';
                                ?>
                                <span class="badge <?= $estadoClass ?>">
                                    <?= $estadoTexto ?>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-credit-card"></i> Método:
                            </div>
                            <div class="col-md-9">
                                <?= $model->metodospagamentos ? Html::encode($model->metodospagamentos->nome) : '<span class="text-muted">Não definido</span>' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-euro-sign"></i> Total:
                            </div>
                            <div class="col-md-9">
                                <strong class="text-success" style="font-size: 1.3em;">
                                    <?= number_format($model->total, 2, ',', '.') ?> €
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Linhas da Fatura -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list text-primary"></i>
                                Linhas da Fatura
                            </h5>

                            <?php if (!$model->estado == 1) : ?>
                                <?= Html::a(
                                    '<i class="fas fa-plus"></i> Adicionar Linha',
                                    ['/linhafatura/create', 'fatura_id' => $model->id],
                                    ['class' => 'btn btn-success btn-sm']
                                ) ?>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-lock"></i> Fatura Paga - Não Editável
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Descrição</th>
                                        <th style="width: 100px;" class="text-center">Quantidade</th>
                                        <th style="width: 150px;" class="text-end">Preço Unit.</th>
                                        <th style="width: 150px;" class="text-end">Total</th>
                                        <th style="width: 120px;" class="text-center">Consulta</th>
                                        <th style="width: 80px;" class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $linhas = $model->linhasfaturas;
                                    if (empty($linhas)): 
                                    ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-2"></i><br>
                                                Nenhuma linha encontrada
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $contador = 1; ?>
                                        <?php foreach ($linhas as $linha): ?>
                                            <tr>
                                                <td><?= $contador++ ?></td>
                                                <td>
                                                    <?php if ($linha->marcacoes_id && $linha->marcacoes): ?>
                                                        <?php 
                                                        $marcacao = $linha->marcacoes;
                                                        $servico = $marcacao->servicos ? $marcacao->servicos->nome : 'Serviço';
                                                        $animal = $marcacao->animais ? ' - ' . $marcacao->animais->nome : '';
                                                        ?>
                                                        <i class="fas fa-calendar-check text-primary"></i>
                                                        <?= Html::a(
                                                            Html::encode($servico . $animal),
                                                            ['/marcacao/view', 'id' => $linha->marcacoes_id],
                                                            ['class' => 'text-decoration-none']
                                                        ) ?>
                                                        <small class="text-muted">(Marcação)</small>
                                                    <?php elseif ($linha->servicos_id && $linha->servicos): ?>
                                                        <i class="fas fa-briefcase-medical text-success"></i>
                                                        <?= Html::encode($linha->servicos->nome) ?>
                                                        <small class="text-muted">(Serviço)</small>
                                                    <?php elseif ($linha->medicamentos_id && $linha->medicamentos): ?>
                                                        <i class="fas fa-pills text-info"></i>
                                                        <?= Html::encode($linha->medicamentos->nome) ?>
                                                        <small class="text-muted">(Medicamento)</small>
                                                    <?php else: ?>
                                                        <span class="text-warning">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                            Item a ser definido
                                                            <?php if (YII_DEBUG): ?>
                                                                (M:<?= $linha->marcacoes_id ?> S:<?= $linha->servicos_id ?> Med:<?= $linha->medicamentos_id ?>)
                                                            <?php endif; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary"><?= $linha->quantidade ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <?= number_format($linha->total / $linha->quantidade, 2, ',', '.') ?> €
                                                </td>
                                                <td class="text-end">
                                                    <strong><?= number_format($linha->total, 2, ',', '.') ?> €</strong>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($linha->vendidoemconsulta): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check"></i> Sim
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-times"></i> Não
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($model->estado == 0): ?>
                                                        <div class="btn-group" role="group">
                                                            <?php 
                                                            // Verifica se é "a ser definido" (sem marcação, serviço ou medicamento)
                                                            $isASerDefinido = !($linha->marcacoes_id && $linha->marcacoes) 
                                                                           && !($linha->servicos_id && $linha->servicos) 
                                                                           && !($linha->medicamentos_id && $linha->medicamentos);
                                                            ?>
                                                            <?php if ($isASerDefinido): ?>
                                                                <?= Html::a(
                                                                    '<i class="fas fa-edit"></i>',
                                                                    ['/linhafatura/update', 'id' => $linha->id],
                                                                    [
                                                                        'class' => 'btn btn-warning btn-sm',
                                                                        'title' => 'Editar',
                                                                    ]
                                                                ) ?>
                                                            <?php endif; ?>
                                                            <?= Html::a(
                                                                '<i class="fas fa-trash"></i>',
                                                                ['/linhafatura/delete', 'id' => $linha->id],
                                                                [
                                                                    'class' => 'btn btn-danger btn-sm',
                                                                    'title' => 'Eliminar',
                                                                    'data' => [
                                                                        'confirm' => 'Tem certeza que deseja eliminar esta linha?',
                                                                        'method' => 'post',
                                                                    ],
                                                                ]
                                                            ) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">
                                                            <i class="fas fa-lock"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">TOTAL:</td>
                                            <td class="text-end fw-bold text-success" style="font-size: 1.1em;">
                                                <?= number_format($model->total, 2, ',', '.') ?> €
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita - Ações -->
            <div class="col-md-4">
                <!-- Card Ações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cog text-primary"></i>
                            Ações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if ($model->estado == 0): ?>
                                <?= Html::a(
                                    '<i class="fas fa-edit"></i> Editar Fatura',
                                    ['update', 'id' => $model->id],
                                    ['class' => 'btn btn-primary btn-block']
                                ) ?>
                            <?php endif;
                            if ($model->estado == 1): ?>
                                <?= Html::a('<i class="fas fa-file-pdf"></i> Exportar PDF',
                                ['pdf',
                                    'nome_emissor' => yii::$app->user->identity->username,
                                    'nome_recep' => $model->userprofiles->nomecompleto,
                                    'id' => $model->id],
                                [
                                    'class'=>'btn btn-secondary btn-block',
                                    'target'=>'_blank',
                                    'data-toggle'=>'tooltip',
                                    'title'=>'Gera e preparar a fatura em formato PDF para impressão e envio.'
                                ]);?>
                            <?php endif; ?>

                            <?php if ($model->eliminado != 1): ?>
                                <?= Html::a(
                                    '<i class="fas fa-trash"></i> Eliminar',
                                    ['delete', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-danger btn-block',
                                        'data' => [
                                            'confirm' => 'Tem certeza que deseja eliminar esta fatura?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Card Informações Adicionais -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informações
                        </h5>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i>
                            <strong>Criada em:</strong><br>
                            <?= $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i') : '-' ?>
                        </small>
                        
                        <?php if ($model->estado == 0): ?>
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                <small>Esta fatura está <strong>pendente</strong> de pagamento.</small>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="fas fa-check-circle"></i>
                                <small>Esta fatura foi <strong>paga</strong>.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

use yii\helpers\Html;
use backend\widgets\SmallCardWidget;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */

$this->title = 'Marcação #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Marcações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-calendar-check text-primary"></i>
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Marcações', ['index']) ?></li>
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
                'icon' => 'fa-calendar',
                'iconColorClass' => 'icon-blue',
                'text' => 'Data',
                'value' => $model->data ? Yii::$app->formatter->asDate($model->data, 'php:d/m/Y') : 'N/A',
                'url' => '#',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-clock',
                'iconColorClass' => 'icon-green',
                'text' => 'Horário',
                'value' => $model->horainicio && $model->horafim ? $model->horainicio . ' - ' . $model->horafim : 'N/A',
                'url' => '#',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-briefcase-medical',
                'iconColorClass' => 'icon-success',
                'text' => 'Serviço',
                'value' => $model->servicos ? Html::encode($model->servicos->nome) : 'N/A',
                'url' => '#',
            ]) ?>

            <?= SmallCardWidget::widget([
                'icon' => 'fa-info-circle',
                'iconColorClass' => $model->estado == 'Confirmada' ? 'icon-success' : 'icon-warning',
                'text' => 'Estado',
                'value' => $model->estado ?? 'N/A',
                'url' => '#',
            ]) ?>
        </div>

        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Card Informações da Marcação -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check text-primary"></i>
                            Informações da Marcação
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-calendar"></i> Data:
                            </div>
                            <div class="col-md-9">
                                <?= $model->data ? Yii::$app->formatter->asDate($model->data, 'php:d/m/Y') : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-clock"></i> Início:
                            </div>
                            <div class="col-md-9">
                                <?= Html::encode($model->horainicio ?? '-') ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-clock"></i> Fim:
                            </div>
                            <div class="col-md-9">
                                <?= Html::encode($model->horafim ?? '-') ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-info-circle"></i> Estado:
                            </div>
                            <div class="col-md-9">
                                <?php
                                $estadoClass = 'bg-secondary';
                                if ($model->estado == 'realizada') $estadoClass = 'bg-success';
                                elseif ($model->estado == 'pendente') $estadoClass = 'bg-warning';
                                elseif ($model->estado == 'cancelada') $estadoClass = 'bg-danger';
                                ?>
                                <span class="badge <?= $estadoClass ?>">
                                    <?= Html::encode($model->estado ?? 'N/A') ?>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-briefcase-medical"></i> Serviço:
                            </div>
                            <div class="col-md-9">
                                <?= $model->servicos ? Html::a(
                                    Html::encode($model->servicos->nome),
                                    ['/servico/view', 'id' => $model->servicos->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-euro-sign"></i> Preço:
                            </div>
                            <div class="col-md-9">
                                <?= $model->servicos && $model->servicos->valor ? number_format($model->servicos->valor, 2, ',', '.') . ' €' : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Diagnóstico -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-stethoscope text-info"></i>
                            Diagnóstico
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($model->diagnostico): ?>
                            <p class="mb-0"><?= nl2br(Html::encode($model->diagnostico)) ?></p>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i>
                                Sem diagnóstico registado.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card Medicamentos Utilizados -->
                <?php if (!empty($medicamentosUtilizados)): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-pills text-success"></i>
                            Medicamentos Utilizados
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-capsules"></i> Medicamento</th>
                                        <th class="text-center"><i class="fas fa-hashtag"></i> Quantidade</th>
                                        <th class="text-end"><i class="fas fa-euro-sign"></i> Preço Unit.</th>
                                        <th class="text-end"><i class="fas fa-calculator"></i> Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalGeral = 0;
                                    foreach ($medicamentosUtilizados as $linha): 
                                        $medicamento = $linha->medicamentos;
                                        if ($medicamento):
                                            $totalLinha = $linha->total;
                                            $totalGeral += $totalLinha;
                                    ?>
                                    <tr>
                                        <td>
                                            <?= Html::a(
                                                Html::encode($medicamento->nome),
                                                ['/medicamento/view', 'id' => $medicamento->id],
                                                ['class' => 'text-decoration-none']
                                            ) ?>
                                            <?php if ($medicamento->descricao): ?>
                                                <br><small class="text-muted"><?= Html::encode($medicamento->descricao) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= Html::encode($linha->quantidade) ?></span>
                                        </td>
                                        <td class="text-end">
                                            <?= number_format($medicamento->preco, 2, ',', '.') ?> €
                                        </td>
                                        <td class="text-end fw-bold">
                                            <?= number_format($totalLinha, 2, ',', '.') ?> €
                                        </td>
                                    </tr>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">
                                            <i class="fas fa-calculator"></i> Total Medicamentos:
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            <?= number_format($totalGeral, 2, ',', '.') ?> €
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Card Relações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-link text-warning"></i>
                            Relações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-paw"></i> Animal:
                            </div>
                            <div class="col-md-9">
                                <?= $model->animais ? Html::a(
                                    Html::encode($model->animais->nome),
                                    ['/animal/view', 'id' => $model->animais->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-user"></i> Cliente:
                            </div>
                            <div class="col-md-9">
                                <?= $model->userprofiles ? Html::a(
                                    Html::encode($model->animais->getDonoNome()),
                                    ['/userprofile/view', 'id' => $model->userprofiles->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita - Ações e Info -->
            <div class="col-md-4">
                <!-- Card Ações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cog text-secondary"></i>
                            Ações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?= Html::a(
                                '<i class="fas fa-edit"></i> Editar',
                                ['update', 'id' => $model->id],
                                ['class' => 'btn btn-primary btn-md']
                            ) ?>
                            
                            <?php 
                            // Verificar se a marcação está realizada e ainda não tem fatura
                            if ($model->estado === \common\models\Marcacao::ESTADO_REALIZADA && !$model->temFatura()): 
                            ?>
                                <?= Html::a(
                                    '<i class="fas fa-file-invoice-dollar"></i> Gerar Fatura',
                                    ['gerar-fatura', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-success btn-md',
                                        'data' => [
                                            'confirm' => 'Deseja gerar uma fatura para esta marcação?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            <?php endif; ?>
                            
                            <?= Html::a(
                                '<i class="fas fa-list"></i> Ver Todos',
                                ['index'],
                                ['class' => 'btn btn-secondary btn-md']
                            ) ?>
                            <?php if ($model->eliminado != 1): ?>
                                <?= Html::a(
                                    '<i class="fas fa-trash"></i> Eliminar',
                                    ['delete', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-danger btn-md',
                                        'data' => [
                                            'confirm' => 'Tem a certeza que deseja eliminar esta marcação?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Card Informação -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info text-info"></i>
                            Informação do Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-calendar-plus"></i> <strong>Criado em:</strong>
                            </small>
                            <small><?= $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '-' ?></small>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-calendar-edit"></i> <strong>Atualizado em:</strong>
                            </small>
                            <small><?= $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : '-' ?></small>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-trash-alt"></i> <strong>Estado:</strong>
                            </small>
                            <?php if ($model->eliminado): ?>
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Eliminado</span>
                            <?php else: ?>
                                <span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

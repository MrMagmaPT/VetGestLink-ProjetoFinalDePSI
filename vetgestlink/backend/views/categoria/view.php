<?php

use yii\helpers\Html;
use backend\widgets\SmallCardWidget;
use backend\widgets\PageHeaderWidget;


/** @var yii\web\View $this */
/** @var common\models\Categoria $model */

$this->title = 'Categoria: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Categorias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nome;

// Contagem de medicamentos
$medicamentosCount = $model->getMedicamentos()->where(['eliminado' => 0])->count();
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Categoria: ' . Html::encode($model->nome),
    'icon' => 'fa-folder text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Categorias',
            'url' => ['index'],
        ],
        [
            'label' => Html::encode($model->nome),
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Resumo -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => 'fa-pills',
                'iconColorClass' => 'icon-blue',
                'text' => 'Medicamentos',
                'value' => $medicamentosCount . ' medicamentos',
                'url' => '/medicamento/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => $model->eliminado ? 'fa-times' : 'fa-check',
                'iconColorClass' => $model->eliminado ? 'icon-red' : 'icon-green',
                'text' => 'Estado',
                'value' => $model->eliminado ? 'Eliminado' : 'Ativo',
                'url' => '#',
            ]) ?>
            
        </div>

        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Card Informações da Categoria -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informações da Categoria
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-folder"></i> Nome:
                            </div>
                            <div class="col-md-9">
                                <strong><?= Html::encode($model->nome) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-pills"></i> Medicamentos:
                            </div>
                            <div class="col-md-9">
                                <?php if ($medicamentosCount > 0): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-pills"></i> <?= $medicamentosCount ?> medicamentos nesta categoria
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Nenhum medicamento associado</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Medicamentos desta Categoria -->
                <?php if ($medicamentosCount > 0): ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-pills text-success"></i>
                                Medicamentos desta Categoria
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php foreach ($medicamentosAtivos as $medicamento): ?>
                                    <?= Html::a(
                                        '<i class="fas fa-capsules text-primary"></i> ' . Html::encode($medicamento->nome) . 
                                        ' <span class="badge bg-secondary float-end">' . number_format($medicamento->preco, 2, ',', '.') . ' €</span>',
                                        ['/medicamento/view', 'id' => $medicamento->id],
                                        ['class' => 'list-group-item list-group-item-action']
                                    ) ?>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($medicamentosCount > 10): ?>
                                <div class="mt-3 text-center">
                                    <?= Html::a(
                                        '<i class="fas fa-list"></i> Ver todos os medicamentos',
                                        ['/medicamento/index'],
                                        ['class' => 'btn btn-outline-primary']
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Coluna Direita - Ações -->
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
                            <?= Html::a(
                                '<i class="fas fa-list"></i> Ver Todas',
                                ['index'],
                                ['class' => 'btn btn-secondary btn-md']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-trash"></i> Eliminar',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-md',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar esta categoria?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>

                <!-- Card Informação -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info text-info"></i>
                            Informação
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Sobre esta categoria:</strong><br>
                                As categorias ajudam a organizar os medicamentos por tipo ou finalidade.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */

$this->title = 'Medicamento: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nome;
\yii\web\YiiAsset::register($this);
$this->registerCssFile('@web/static/css/view.css');
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-pills text-primary"></i>
                    <?= Html::encode($model->nome) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Medicamentos', ['index']) ?></li>
                    <li class="breadcrumb-item active"><?= Html::encode($model->nome) ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Card Informações do Medicamento -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informações do Medicamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-capsules"></i> Nome:
                            </div>
                            <div class="col-md-8">
                                <strong><?= Html::encode($model->nome) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-file-medical"></i> Descrição:
                            </div>
                            <div class="col-md-8">
                                <?= Html::encode($model->descricao) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-euro-sign"></i> Preço:
                            </div>
                            <div class="col-md-8">
                                <strong class="text-success"><?= number_format($model->preco, 2, ',', '.') ?> €</strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-boxes"></i> Quantidade em Stock:
                            </div>
                            <div class="col-md-8">
                                <?php
                                if ($model->quantidade < 5) {
                                    echo '<span class="badge bg-danger"><i class="fas fa-skull-crossbones"></i> Stock Crítico: ' . $model->quantidade . '</span>';
                                } elseif ($model->quantidade < 10) {
                                    echo '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Stock Baixo: ' . $model->quantidade . '</span>';
                                } else {
                                    echo '<span class="badge bg-success"><i class="fas fa-check"></i> Stock Bom: ' . $model->quantidade . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-folder"></i> Categoria:
                            </div>
                            <div class="col-md-8">
                                <?php 
                                $categoria = $model->categoria;
                                if ($categoria) {
                                    echo Html::a(
                                        '<span class="badge bg-info">' . Html::encode($categoria->nome) . '</span>',
                                        ['/categoria/view', 'id' => $categoria->id]
                                    );
                                } else {
                                    echo '<span class="text-muted">Sem categoria</span>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-toggle-on"></i> Estado:
                            </div>
                            <div class="col-md-8">
                                <?php if ($model->eliminado == 1): ?>
                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Eliminado</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>
                                <?php endif; ?>
                            </div>
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
                                '<i class="fas fa-list"></i> Ver Todos',
                                ['index'],
                                ['class' => 'btn btn-secondary btn-md']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-trash"></i> Eliminar',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-md',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar este medicamento?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>

                <!-- Card Informações Adicionais -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="fas fa-hashtag text-muted"></i>
                            <strong>Legenda do Stock:</strong>
                        </p>
                        <hr>
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Níveis de Stock:</strong><br>
                                <span class="badge bg-success">≥ 10</span> Stock Bom<br>
                                <span class="badge bg-warning">5 - 9</span> Stock Baixo<br>
                                <span class="badge bg-danger">< 5</span> Stock Crítico
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

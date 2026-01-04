<?php

use yii\helpers\Html;
use backend\widgets\SmallCardWidget;

/** @var yii\web\View $this */
/** @var common\models\Raca $model */

$this->title = 'Raça: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Raças', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nome;
\yii\web\YiiAsset::register($this);
$this->registerCssFile('@web/static/css/view.css');
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-dog text-primary"></i>
                    <?= Html::encode($model->nome) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Raças', ['index']) ?></li>
                    <li class="breadcrumb-item active"><?= Html::encode($model->nome) ?></li>
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
                'icon' => 'fa-dog',
                'iconColorClass' => 'icon-blue',
                'text' => 'Raça',
                'value' => Html::encode($model->nome),
                'url' => '#',
            ]) ?>
            <?= SmallCardWidget::widget([
                'icon' => 'fa-tags',
                'iconColorClass' => 'icon-purple',
                'text' => 'Espécie',
                'value' => $model->especies ? Html::encode($model->especies->nome) : '-',
                'url' => $model->especies ? '/especie/view?id=' . $model->especies->id : '#',
            ]) ?>
            <?= SmallCardWidget::widget([
                'icon' => $model->eliminado ? 'fa-times' : 'fa-check',
                'iconColorClass' => $model->eliminado ? 'icon-red' : 'icon-green',
                'text' => 'Estado',
                'value' => $model->eliminado ? 'Eliminada' : 'Ativa',
                'url' => '#',
            ]) ?>
        </div>

        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informações da Raça
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-signature"></i> Nome:
                            </div>
                            <div class="col-md-9">
                                <strong><?= Html::encode($model->nome) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-tags"></i> Espécie:
                            </div>
                            <div class="col-md-9">
                                <?= $model->especies ? Html::a(
                                    Html::encode($model->especies->nome),
                                    ['/especie/view', 'id' => $model->especies->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-check-circle"></i> Estado:
                            </div>
                            <div class="col-md-9">
                                <?php if ($model->eliminado): ?>
                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Eliminada</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Ativa</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita - Ações -->
            <div class="col-md-4">
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
                                ['class' => 'btn btn-primary btn-lg']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-list"></i> Ver Todas',
                                ['index'],
                                ['class' => 'btn btn-secondary btn-lg']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-trash"></i> Eliminar',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-lg',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar esta raça?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
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
                                <i class="fas fa-lightbulb"></i>
                                Raças são subgrupos de espécies, como Labrador, Poodle, Pastor Alemão, etc.<br>
                                <i class="fas fa-paw"></i>
                                Cada raça pertence a uma espécie.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use backend\widgets\SmallCardWidget;

/** @var yii\web\View $this */
/** @var common\models\Metodopagamento $model */

$this->title = 'Método de Pagamento: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Métodos de Pagamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nome;
\yii\web\YiiAsset::register($this);
$this->registerCssFile('@web/static/css/view.css');
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-credit-card text-primary"></i>
                    <?= Html::encode($model->nome) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Métodos de Pagamento', ['index']) ?></li>
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
                'icon' => $model->vigor ? 'fa-check-circle' : 'fa-times-circle',
                'iconColorClass' => $model->vigor ? 'icon-green' : 'icon-red',
                'text' => 'Estado de Vigor',
                'value' => $model->vigor ? 'Ativo' : 'Inativo',
                'url' => '#',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => $model->eliminado ? 'fa-times' : 'fa-check',
                'iconColorClass' => $model->eliminado ? 'icon-red' : 'icon-green',
                'text' => 'Estado',
                'value' => $model->eliminado ? 'Eliminado' : 'Ativo',
                'url' => '#',
            ]) ?>
            
            <!-- Não é necessário mostrar o ID do método de pagamento em um card separado -->
        </div>

        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Card Informações do Método -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informações do Método de Pagamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-credit-card"></i> Nome:
                            </div>
                            <div class="col-md-9">
                                <strong><?= Html::encode($model->nome) ?></strong>
                            </div>
                        </div>
                        <!-- Onde Mostrava o ID do método de pagamento -->
                        <!-- <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-hashtag"></i> ID:
                            </div>
                            <div class="col-md-9">
                                <?= Html::encode($model->id) ?>
                            </div>
                        </div> -->
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-toggle-on"></i> Estado de Vigor:
                            </div>
                            <div class="col-md-9">
                                <?php if ($model->vigor): ?>
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Em Vigor</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Sem Vigor</span>
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
                                        'confirm' => 'Tem a certeza que deseja eliminar este método de pagamento?',
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
                                <strong>Sobre este método:</strong><br>
                                Os métodos de pagamento definem as formas aceitas para pagamento de faturas e serviços.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

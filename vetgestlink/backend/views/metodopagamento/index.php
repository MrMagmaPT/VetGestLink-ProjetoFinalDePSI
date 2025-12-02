<?php

use common\models\Metodopagamento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;

/** @var yii\web\View $this */
/** @var backend\models\MetodopagamentoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $activeCount */
/** @var int $inactiveCount */

$this->title = 'Gestão de Métodos de Pagamento';
$this->params['breadcrumbs'][] = 'Métodos de Pagamento';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-credit-card text-primary"></i>
                    Métodos de Pagamento
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item active">Métodos de Pagamento</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Card de Estatísticas -->
        <div class="row mb-4">
            <?= BigCardWidget::widget([
                'icon' => 'fa-credit-card',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Métodos',
                'value' => $totalCount,
                'url' => '/metodopagamento/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-check-circle',
                'iconColorClass' => 'icon-green',
                'text' => 'Métodos Ativos',
                'value' => $activeCount,
                'url' => '/metodopagamento/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-times-circle',
                'iconColorClass' => 'icon-red',
                'text' => 'Métodos Inativos',
                'value' => $inactiveCount,
                'url' => '/metodopagamento/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Métodos de Pagamento
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Novo Método',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 50px'],
                        ],
                        [
                            'attribute' => 'nome',
                            'label' => 'Nome do Método',
                            'format' => 'raw',
                            'value' => function($model) {
                                $icon = 'fa-money-bill';
                                if (stripos($model->nome, 'cart') !== false) {
                                    $icon = 'fa-credit-card';
                                } elseif (stripos($model->nome, 'mb') !== false || stripos($model->nome, 'multibanco') !== false) {
                                    $icon = 'fa-university';
                                } elseif (stripos($model->nome, 'dinheiro') !== false || stripos($model->nome, 'cash') !== false) {
                                    $icon = 'fa-money-bill-wave';
                                } elseif (stripos($model->nome, 'transfer') !== false) {
                                    $icon = 'fa-exchange-alt';
                                }
                                
                                return Html::a(
                                    '<i class="fas ' . $icon . ' text-primary"></i> <strong>' . Html::encode($model->nome) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                        ],
                        [
                            'attribute' => 'vigor',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->vigor == 1) {
                                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>';
                                }
                                return '<span class="badge bg-danger"><i class="fas fa-times"></i> Inativo</span>';
                            },
                            'filter' => [
                                0 => 'Inativo',
                                1 => 'Ativo',
                            ],
                            'headerOptions' => ['style' => 'width: 120px'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                        [
                            'class' => ActionColumn::class,
                            'header' => 'Ações',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a(
                                        '<i class="fas fa-eye"></i>',
                                        $url,
                                        [
                                            'class' => 'btn btn-sm btn-info',
                                            'title' => 'Ver',
                                            'data-toggle' => 'tooltip',
                                        ]
                                    );
                                },
                                'update' => function ($url, $model) {
                                    return Html::a(
                                        '<i class="fas fa-edit"></i>',
                                        $url,
                                        [
                                            'class' => 'btn btn-sm btn-primary',
                                            'title' => 'Editar',
                                            'data-toggle' => 'tooltip',
                                        ]
                                    );
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a(
                                        '<i class="fas fa-trash"></i>',
                                        $url,
                                        [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Eliminar',
                                            'data-toggle' => 'tooltip',
                                            'data-confirm' => 'Tem a certeza que deseja eliminar este método de pagamento?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Metodopagamento $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>


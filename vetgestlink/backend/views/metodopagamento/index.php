<?php

use common\models\Metodopagamento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;
use backend\widgets\SmallCardWidget;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\MetodopagamentoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $activeCount */
/** @var int $inactiveCount */

$this->title = 'Gestão de Métodos de Pagamento';
$this->params['breadcrumbs'][] = 'Métodos de Pagamento';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Métodos de Pagamento',
    'icon' => 'fa-credit-card text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Métodos de Pagamento',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Card de Estatísticas -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
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
            
            <?= SmallCardWidget::widget([
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
                                <div class="row mb-3 mt-3">
                    <div class="col-md-6">
                    <!-- Barra de pesquisa Select2 para Método de Pagamento -->
                    <?= kartik\select2\Select2::widget([
                        'name' => 'search_metodopagamento',
                        'data' => $searchModel::getMetodosList(),
                        'value' => $searchModel->nome,
                        'options' => [
                            'placeholder' => 'Pesquisar método...',
                            'id' => 'metodopagamento-search',
                            'class' => 'form-control form-control-sm',
                            'style' => 'max-width: 50px; font-size: 0.7rem; display: inline-block; padding: 1px 2px;',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'noResults' => new \yii\web\JsExpression('function() { return "Nenhum método encontrado"; }'),
                            ],
                            'templateResult' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                        ],
                        'bsVersion' => '5.x',
                        'pluginEvents' => [
                            'select2:select' => 'function(e) { 
                                var nome = e.params.data.id;
                                window.location.href = "' . Url::to(['index']) . '?MetodopagamentoSearch[nome]=" + encodeURIComponent(nome);
                            }',
                            'select2:clear' => 'function(e) {
                                window.location.href = "' . Url::to(['index']) . '";
                            }',
                        ],
                    ]); ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n{pager}",
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Nenhum Método de pagamento encontrado com esse nome.</div>',
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
                            'headerOptions' => ['style' => 'width: 100px'],
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
                            'filter' => false,
                        ],
                        [
                            
                            'headerOptions' => ['style' => 'width: 100px'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'vigor',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->vigor == 1) {
                                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>';
                                }
                                return '<span class="badge bg-danger"><i class="fas fa-times"></i> Inativo</span>';
                            },
                            'filter' => kartik\select2\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'eliminado',
                                'data' => [
                                    0 => 'Ativo',
                                    1 => 'Eliminado',
                                ],
                                'options' => [
                                    'placeholder' => 'Estado...',
                                    'allowClear' => true,
                                    'style' => 'width: 120px;',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhum estado encontrado"; }'),
                                    ],
                                ],
                                'bsVersion' => '5.x',
                            ]),
                        ],
                        [
                            'class' => ActionColumn::class,
                            'header' => 'Ações',
                            'template' => '<div style="display: flex; gap: 8px; justify-content: center;">{view}{update}{delete}</div>',
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


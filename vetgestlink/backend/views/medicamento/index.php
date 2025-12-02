<?php

use common\models\Medicamento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;

/** @var yii\web\View $this */
/** @var backend\models\MedicamentoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $stockCritico */
/** @var int $stockBaixo */
/** @var int $stockBom */

$this->title = 'Gestão de Medicamentos';
$this->params['breadcrumbs'][] = 'Medicamentos';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-pills text-primary"></i>
                    Medicamentos
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item active">Medicamentos</li>
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
                'icon' => 'fa-pills',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Medicamentos',
                'value' => $totalCount,
                'url' => '/medicamento/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-check-circle',
                'iconColorClass' => 'icon-green',
                'text' => 'Stock Bom',
                'value' => $stockBom,
                'url' => '/medicamento/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-exclamation-triangle',
                'iconColorClass' => 'icon-orange',
                'text' => 'Stock Baixo',
                'value' => $stockBaixo,
                'url' => '/medicamento/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-skull-crossbones',
                'iconColorClass' => 'icon-red',
                'text' => 'Stock Crítico',
                'value' => $stockCritico,
                'url' => '/medicamento/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Medicamentos
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Novo Medicamento',
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
                            'label' => 'Nome',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(
                                    '<strong>' . Html::encode($model->nome) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                        ],
                        [
                            'attribute' => 'descricao',
                            'label' => 'Descrição',
                            'value' => function($model) {
                                return strlen($model->descricao) > 50 
                                    ? substr($model->descricao, 0, 50) . '...' 
                                    : $model->descricao;
                            },
                        ],
                        [
                            'attribute' => 'preco',
                            'label' => 'Preço',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<strong>' . number_format($model->preco, 2, ',', '.') . ' €</strong>';
                            },
                            'headerOptions' => ['style' => 'width: 100px'],
                            'contentOptions' => ['style' => 'text-align: right'],
                        ],
                        [
                            'attribute' => 'quantidade',
                            'label' => 'Stock',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->quantidade < 5) {
                                    return '<span class="badge bg-danger"><i class="fas fa-skull-crossbones"></i> ' . $model->quantidade . '</span>';
                                } elseif ($model->quantidade < 10) {
                                    return '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> ' . $model->quantidade . '</span>';
                                }
                                return '<span class="badge bg-success"><i class="fas fa-check"></i> ' . $model->quantidade . '</span>';
                            },
                            'headerOptions' => ['style' => 'width: 100px'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                        [
                            'attribute' => 'categorias_id',
                            'label' => 'Categoria',
                            'value' => function($model) {
                                $categoria = $model->getCategorias()->one();
                                return $categoria ? $categoria->nome : '-';
                            },
                            'headerOptions' => ['style' => 'width: 150px'],
                        ],
                        [
                            'attribute' => 'eliminado',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->eliminado == 1) {
                                    return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminado</span>';
                                }
                                return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>';
                            },
                            'filter' => [
                                0 => 'Ativo',
                                1 => 'Eliminado',
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
                                            'data-confirm' => 'Tem a certeza que deseja eliminar este medicamento?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Medicamento $model, $key, $index, $column) {
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

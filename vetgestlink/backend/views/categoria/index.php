<?php

use common\models\Categoria;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;
use backend\widgets\SmallCardWidget;
use backend\widgets\PageHeaderWidget;


/** @var yii\web\View $this */
/** @var backend\models\CategoriaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $deletedCount */
/** @var int $medicamentosCount */

$this->title = 'Gestão de Categorias';
$this->params['breadcrumbs'][] = 'Categorias';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Categorias',
    'icon' => 'fa-folder text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Categorias',
        ],
    ],
]); 
?>

<div class="content">
    <div class="container-fluid">
        <!-- Card de Estatísticas -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => 'fa-folder',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Categorias',
                'value' => $totalCount,
                'url' => '/categoria/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-pills',
                'iconColorClass' => 'icon-green',
                'text' => 'Total de Medicamentos',
                'value' => $medicamentosCount,
                'url' => '/medicamento/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-folder-minus',
                'iconColorClass' => 'icon-yellow',
                'text' => 'Categorias Eliminadas',
                'value' => $deletedCount,
                'url' => '/categoria/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Categorias
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Nova Categoria',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
                <div class="row mb-3 mt-3">
                    <div class="col-md-6">
                        <!-- Barra de pesquisa com Select2 -->
                        <?= kartik\select2\Select2::widget([
                            'name' => 'search_categoria',
                            'data' => $searchModel->getCategoriasList(),
                            'value' => $searchModel->id,
                            'options' => [
                                'placeholder' => 'Pesquisar categoria...',
                                'id' => 'categoria-search',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'language' => [
                                    'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma categoria encontrada"; }'),
                                ],
                                'templateResult' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                                'templateSelection' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                            ],
                            'bsVersion' => '5.x',
                            'pluginEvents' => [
                                'select2:select' => 'function(e) { 
                                    var id = e.params.data.id;
                                    window.location.href = "' . Url::to(['index']) . '?CategoriaSearch[id]=" + id;
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
                <?php \yii\widgets\Pjax::begin(['id' => 'categoria-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n\n{pager}",
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Não foi encontrado</div>',
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 50px '],
                        ],
                        [
                            'attribute' => 'nome',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'label' => 'Nome da Categoria',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(
                                    '<i class="fas fa-folder"></i> <strong>' . Html::encode($model->nome) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'medicamentos_count',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Medicamentos',
                            'format' => 'raw',
                            'value' => function($model) {
                                $count = $model->getMedicamentos()->where(['eliminado' => 0])->count();
                                if ($count > 0) {
                                    return '<span class="badge bg-info"><i class="fas fa-pills"></i> ' . $count . '</span>';
                                }
                                return '<span class="text-muted">0</span>';
                            },
                            'enableSorting' => false,
                        ],
                        [
                            'attribute' => 'eliminado',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->eliminado == 1) {
                                    return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminado</span>';
                                }
                                return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>';
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
                            'header' => '<i class="fas fa-cog"></i> Ações',
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
                                            'data-confirm' => 'Tem a certeza que deseja eliminar esta categoria?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Categoria $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

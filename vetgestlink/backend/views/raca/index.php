<?php

use common\models\Raca;
use common\models\Especie;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\widgets\BigCardWidget;
use yii\widgets\Pjax;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\RacaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Raças';
$this->params['breadcrumbs'][] = 'Raças';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Raças',
    'icon' => 'fa-dog text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Raças',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <?= BigCardWidget::widget([
                'icon' => 'fa-dog',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Raças',
                'value' => $totalCount,
                'url' => '/raca/index',
            ]) ?>
            <?= BigCardWidget::widget([
                'icon' => 'fa-dog',
                'iconColorClass' => 'icon-gray',
                'text' => 'Eliminadas',
                'value' => $deletedCount,
                'url' => '/raca/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Raças
                    </h5>
                    <?php if (Yii::$app->user->can('createBreed')): ?>
                        <?= Html::a(
                            '<i class="fas fa-plus"></i> Nova Raça',
                            ['create'],
                            ['class' => 'btn btn-success']
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Barra de Pesquisa com Select2 -->
                <div class="row mb-2">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white" style="width: 45px;">
                                <i class="fas fa-dog"></i>
                            </span>
                            <?= kartik\select2\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'nome',
                                'data' => $searchModel::getActiveList(),
                                'options' => [
                                    'placeholder' => 'Pesquisar por nome...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma raça encontrada"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) {
                                        var nome = e.params.data.id;
                                        $.pjax.reload({container: "#raca-grid", url: "' . Url::to(['index']) . '?RacaSearch[nome]=" + encodeURIComponent(nome)});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#raca-grid", url: "' . Url::to(['index']) . '"});
                                    }',
                                ],
                                'bsVersion' => '5.x',
                            ]); ?>
                        </div>
                    </div>
                </div>

                <!-- Barra de Filtros Rápidos -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="text-muted fw-bold me-2">
                                <i class="fas fa-filter"></i> Filtros Rápidos:
                            </span>
                            <?= Html::a(
                                '<i class="fas fa-list"></i> Todas',
                                ['index'],
                                ['class' => 'btn btn-sm btn-outline-secondary']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-check"></i> Ativas',
                                ['index', 'RacaSearch[eliminado]' => 0],
                                ['class' => 'btn btn-sm btn-outline-success']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-times"></i> Eliminadas',
                                ['index', 'RacaSearch[eliminado]' => 1],
                                ['class' => 'btn btn-sm btn-outline-danger']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-times"></i> Limpar Filtros',
                                ['index'],
                                ['class' => 'btn btn-sm btn-outline-dark']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <?php Pjax::begin(['id' => 'raca-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end} raças</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n{pager}",
                    //Mudar a mensagem quando não houver resultados
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Não foi encontrado.</div>',
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 50px'],
                        ],
                        [
                            'attribute' => 'nome',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(
                                    '<strong>' . Html::encode($model->nome) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                            'filter' => false,
                            'headerOptions' => ['style' => 'width: 180px'],
                        ],
                        [
                            'attribute' => 'especies_id',
                            'label' => 'Espécie',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->especies ? Html::a(
                                    Html::encode($model->especies->nome),
                                    ['/especie/view', 'id' => $model->especies->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-';
                            },
                            'filter' => false,
                            // 'filter' => $especiesAtivas,
                            'headerOptions' => ['style' => 'width: 180px'],
                        ],
                        [
                            'attribute' => 'eliminado',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->eliminado == 1) {
                                    return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminada</span>';
                                }
                                return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativa</span>';
                            },
                            'filter' => false,
                            // 'filter' => kartik\select2\Select2::widget([
                            //     'model' => $searchModel,
                            //     'attribute' => 'eliminado',
                            //     'data' => [
                            //         0 => 'Ativa',
                            //         1 => 'Eliminada',
                            //     ],
                            //     'options' => [
                            //         'placeholder' => 'Estado...',
                            //         'allowClear' => true,
                            //         'style' => 'width: 120px;',
                            //     ],
                            //     'pluginOptions' => [
                            //         'allowClear' => true,
                            //         'language' => [
                            //             'noResults' => new \yii\web\JsExpression('function() { return "Nenhum estado encontrado"; }'),
                            //         ],
                            //     ],
                            //     'bsVersion' => '5.x',
                            // ]),
                            'headerOptions' => ['style' => 'width: 120px'],
                            'contentOptions' => ['style' => 'text-align: center'],
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
                                    if (!Yii::$app->user->can('updateBreed')) {
                                        return '';
                                    }
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
                                    if (!Yii::$app->user->can('deleteBreed')) {
                                        return '';
                                    }
                                    return Html::a(
                                        '<i class="fas fa-trash"></i>',
                                        $url,
                                        [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Eliminar',
                                            'data-toggle' => 'tooltip',
                                            'data-confirm' => 'Tem a certeza que deseja eliminar esta raça?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Raca $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

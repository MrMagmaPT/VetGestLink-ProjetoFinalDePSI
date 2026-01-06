<?php

use common\models\Especie;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\EspecieSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Espécies';
$this->params['breadcrumbs'][] = 'Espécies';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Espécies',
    'icon' => 'fa-paw text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Espécies',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <?= BigCardWidget::widget([
                'icon' => 'fa-paw',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Espécies',
                'value' => $totalCount,
                'url' => '/especie/index',
            ]) ?>
            <?= BigCardWidget::widget([
                'icon' => 'fa-paw',
                'iconColorClass' => 'icon-red',
                'text' => 'Eliminadas',
                'value' => $deletedCount,
                'url' => '/especie/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Espécies
                    </h5>
                    <?php if (Yii::$app->user->can('createSpecies')): ?>
                        <?= Html::a(
                            '<i class="fas fa-plus"></i> Nova Espécie',
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
                                <i class="fas fa-paw"></i>
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
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma espécie encontrada"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var nome = e.params.data.id;
                                        $.pjax.reload({container: "#especie-grid", url: "' . Url::to(['index']) . '?EspecieSearch[nome]=" + encodeURIComponent(nome)});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#especie-grid", url: "' . Url::to(['index']) . '"});
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
                                ['index', 'EspecieSearch[eliminado]' => 0],
                                ['class' => 'btn btn-sm btn-outline-success']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-times"></i> Eliminadas',
                                ['index', 'EspecieSearch[eliminado]' => 1],
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
                <?php \yii\widgets\Pjax::begin(['id' => 'especie-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end} espécies</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n{pager}",
                    //Mudar a mensagem quando não houver resultados
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Não foi encontrado.</div>',
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'pager' => [
                        'class' => 'yii\bootstrap5\LinkPager',
                        'options' => ['class' => 'pagination justify-content-center'],
                        'linkOptions' => ['class' => 'page-link'],
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'prevPageLabel' => '<i class="fas fa-chevron-left"></i> Anterior',
                        'nextPageLabel' => 'Próximo <i class="fas fa-chevron-right"></i>',
                        'firstPageLabel' => '<i class="fas fa-angle-double-left"></i> Primeiro',
                        'lastPageLabel' => 'Último <i class="fas fa-angle-double-right"></i>',
                        'maxButtonCount' => 5,
                    ],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 50px'],
                        ],
                        [
                            'attribute' => 'nome',
                            'headerOptions' => ['style' => 'width: 180px'],
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(
                                    '<strong>' . Html::encode($model->nome) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'eliminado',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
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
                                    if (!Yii::$app->user->can('updateSpecies')) {
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
                                    if (!Yii::$app->user->can('deleteSpecies')) {
                                        return '';
                                    }
                                    return Html::a(
                                        '<i class="fas fa-trash"></i>',
                                        $url,
                                        [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Eliminar',
                                            'data-toggle' => 'tooltip',
                                            'data-confirm' => 'Tem a certeza que deseja eliminar esta espécie?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Especie $model, $key, $index, $column) {
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

<?php

use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\PageHeaderWidget;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var backend\models\FaturaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

echo PageHeaderWidget::widget([
    'title' => 'Faturas',
    'icon' => 'fa-file-invoice-dollar text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Faturas',
        ],
    ],
]);
?>
<div class="content">
    <div class="container-fluid">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <?= \backend\widgets\SmallCardWidget::widget([
                'icon' => 'fa-file-invoice-dollar',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Faturas',
                'value' => isset($totalCount) ? $totalCount : '',
                'url' => '/fatura/index',
            ]) ?>
            <?= \backend\widgets\SmallCardWidget::widget([
                'icon' => 'fa-check',
                'iconColorClass' => 'icon-green',
                'text' => 'Faturas Pagas',
                'value' => isset($paidCount) ? $paidCount : '',
                'url' => '/fatura/index?FaturaSearch[estado]=1',
            ]) ?>
            <?= \backend\widgets\SmallCardWidget::widget([
                'icon' => 'fa-times',
                'iconColorClass' => 'icon-red',
                'text' => 'Faturas Pendentes',
                'value' => isset($pendingCount) ? $pendingCount : '',
                'url' => '/fatura/index?FaturaSearch[estado]=0',
            ]) ?>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Faturas
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Nova Fatura',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Barra de Pesquisa com Select2 -->
                <div class="row mb-2">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white" style="width: 45px;">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                            <?= Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'id',
                                'data' => $faturasList ?? [],
                                'options' => [
                                    'placeholder' => 'Pesquisar por nº...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma fatura encontrada"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var faturaId = e.params.data.id;
                                        $.pjax.reload({container: "#fatura-grid", url: "' . Url::to(['index']) . '?FaturaSearch[id]=" + faturaId});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#fatura-grid", url: "' . Url::to(['index']) . '"});
                                    }',
                                ],
                                'bsVersion' => '5.x',
                            ]); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-info text-white" style="width: 45px;">
                                <i class="fas fa-credit-card"></i>
                            </span>
                            <?= Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'metodospagamentos_id',
                                'data' => $metodosPagamentoList ?? [],
                                'options' => [
                                    'placeholder' => 'Pesquisar por método...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhum método encontrado"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var metodoId = e.params.data.id;
                                        $.pjax.reload({container: "#fatura-grid", url: "' . Url::to(['index']) . '?FaturaSearch[metodospagamentos_id]=" + metodoId});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#fatura-grid", url: "' . Url::to(['index']) . '"});
                                    }',
                                ],
                                'bsVersion' => '5.x',
                            ]); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white" style="width: 45px;">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <?= Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'estado',
                                'data' => $estadosList ?? [],
                                'options' => [
                                    'placeholder' => 'Pesquisar por estado...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhum estado encontrado"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var estado = e.params.data.id;
                                        $.pjax.reload({container: "#fatura-grid", url: "' . Url::to(['index']) . '?FaturaSearch[estado]=" + estado});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#fatura-grid", url: "' . Url::to(['index']) . '"});
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
                                '<i class="fas fa-check"></i> Pagas',
                                ['index', 'FaturaSearch[estado]' => 1],
                                ['class' => 'btn btn-sm btn-outline-success']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-clock"></i> Pendentes',
                                ['index', 'FaturaSearch[estado]' => 0],
                                ['class' => 'btn btn-sm btn-outline-warning']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-check-circle"></i> Ativas',
                                ['index', 'FaturaSearch[eliminado]' => 0],
                                ['class' => 'btn btn-sm btn-outline-primary']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-trash"></i> Eliminadas',
                                ['index', 'FaturaSearch[eliminado]' => 1],
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
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?php \yii\widgets\Pjax::begin(['id' => 'fatura-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n{pager}",
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Nenhuma fatura encontrada.</div>',
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
                            'headerOptions' => ['style' => 'width: 80px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
//                         [
//                             'attribute' => 'id',
//                             'label' => 'Nº Fatura',
//                             'headerOptions' => ['style' => 'width: 100px; text-align: center'],
//                             'contentOptions' => ['style' => 'text-align: center'],
//                             'filter' => false,
// //                            'filter' => Html::activeTextInput($searchModel, 'id', [
// //                                'class' => 'form-control form-control-sm',
// //                                'placeholder' => 'Nº...',
// //                                'style' => 'width: 100px;'
// //                            ]),
//                         ],
                        [
                            'attribute' => 'total',
                            'headerOptions' => ['style' => 'width: 50px; text-align: right'],
                            'contentOptions' => ['style' => 'text-align: right'],
                            'format' => ['decimal', 2],
                            'filter' => false,
//                            'filter' => Html::activeTextInput($searchModel, 'total', [
//                                'class' => 'form-control form-control-sm',
//                                'placeholder' => 'Total...',
//                                'style' => 'width: 120px;'
//                            ]),
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'Data',
                            'headerOptions' => ['style' => 'width: 150px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'format' => ['datetime', 'php:d/m/Y H:i'],
                            'filter' => false,
//                            'filter' => Html::activeInput('date', $searchModel, 'created_at', [
//                                'class' => 'form-control form-control-sm',
//                                'style' => 'width: 150px;'
//                            ]),
                        ],
                        [
                            'attribute' => 'estado',
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'filter' => false,
//                            'filter' => Html::activeDropDownList($searchModel, 'estado',
//                                ['' => 'Todos...', 0 => 'Pendente', 1 => 'Paga'],
//                                [
//                                    'class' => 'form-control form-control-sm',
//                                    'style' => 'width: 120px;'
//                                ]
//                            ),
                            'value' => function($model) {
                                if ($model->estado == 1) {
                                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Paga</span>';
                                } else {
                                    return '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pendente</span>';
                                }
                            },
                            'format' => 'raw',
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px'],
                            'attribute' => 'metodospagamentos_id',
                            'label' => 'Método de Pagamento',
                            'value' => function($model) {
                                return $model->metodospagamentos ? $model->metodospagamentos->nome : 'Não definido';
                            },
                            'filter' => false,
//                            'filter' => Html::activeTextInput($searchModel, 'metodospagamentos_id', [
//                                'class' => 'form-control form-control-sm',
//                                'placeholder' => 'Método...',
//                                'style' => 'width: 180px;'
//                            ]),
                        ],
                        [
                            'attribute' => 'eliminado',
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => function($model) {
                                return ['style' => 'text-align: center; ' . ($model->eliminado ? 'color: #666;' : '')];
                            },
                            'filter' => false,
//                            'filter' => Html::activeDropDownList($searchModel, 'eliminado',
//                                ['' => 'Todos...', 0 => 'Ativa', 1 => 'Eliminada'],
//                                [
//                                    'class' => 'form-control form-control-sm',
//                                    'style' => 'width: 120px;'
//                                ]
//                            ),
                            'value' => function($model) {
                                if ($model->eliminado) {
                                    return '<span class="badge bg-danger"><i class="fas fa-trash"></i> Eliminada</span>';
                                } else {
                                    return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Ativa</span>';
                                }
                            },
                            'format' => 'raw',
                        ],
                        [
                            'class' => ActionColumn::className(),
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
                                    // Só mostrar botão de editar se a fatura estiver pendente
                                    if ($model->estado == 0) {
                                        return Html::a(
                                            '<i class="fas fa-edit"></i>',
                                            $url,
                                            [
                                                'class' => 'btn btn-sm btn-primary',
                                                'title' => 'Editar',
                                                'data-toggle' => 'tooltip',
                                            ]
                                        );
                                    }
                                    return '';
                                },
                                'delete' => function ($url, $model) {
                                    if ($model->eliminado == 1) {
                                        return '';
                                    }
                                    return Html::a(
                                        '<i class="fas fa-trash"></i>',
                                        $url,
                                        [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Eliminar',
                                            'data-toggle' => 'tooltip',
                                            'data-confirm' => 'Tem a certeza que deseja eliminar esta fatura?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Fatura $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                    ]
                ]);
                ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

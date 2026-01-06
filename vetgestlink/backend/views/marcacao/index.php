<?php

use common\models\Marcacao;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;
use kartik\select2\Select2;
use yii\web\JsExpression;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\MarcacaoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $pendenteCount */
/** @var int $realizadaCount */
/** @var int $canceladaCount */


// Setar o título da página
$this->title = 'Gestão de Marcações';
// Breadcrumbs(Substituído pelo header personalizado)
$this->params['breadcrumbs'][] = 'Marcações';
?>
<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Marcações',
    'icon' => 'fa-calendar-check text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Marcações',
        ],
    ],
]);
?>
<div class="content">
    <div class="container-fluid">
        <!-- Card de Estatísticas -->
        <div class="row mb-4">
            <?= BigCardWidget::widget([
                'icon' => 'fa-calendar-check',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Marcações',
                'value' => $totalCount,
                'url' => '/marcacao/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-clock',
                'iconColorClass' => 'icon-yellow',
                'text' => 'Pendentes',
                'value' => $pendenteCount,
                'url' => '/marcacao/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-check-circle',
                'iconColorClass' => 'icon-green',
                'text' => 'Realizadas',
                'value' => $realizadaCount,
                'url' => '/marcacao/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-times-circle',
                'iconColorClass' => 'icon-red',
                'text' => 'Canceladas',
                'value' => $canceladaCount,
                'url' => '/marcacao/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Marcações
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Nova Marcação',
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
                                <i class="fas fa-calendar"></i>
                            </span>
                            <?= Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'data',
                                'data' => $datasList ?? [],
                                'options' => [
                                    'placeholder' => 'Pesquisar por data...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new JsExpression('function() { return "Nenhuma data encontrada"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var data = e.params.data.id;
                                        $.pjax.reload({container: "#marcacao-grid", url: "' . Url::to(['index']) . '?MarcacaoSearch[data]=" + data});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#marcacao-grid", url: "' . Url::to(['index']) . '"});
                                    }',
                                ],
                                'bsVersion' => '5.x',
                            ]); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-info text-white" style="width: 45px;">
                                <i class="fas fa-paw"></i>
                            </span>
                            <?= Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'animais_id',
                                'data' => $animaisList,
                                'options' => [
                                    'placeholder' => 'Pesquisar por animal...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new JsExpression('function() { return "Nenhum animal encontrado"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var animalId = e.params.data.id;
                                        $.pjax.reload({container: "#marcacao-grid", url: "' . Url::to(['index']) . '?MarcacaoSearch[animais_id]=" + animalId});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#marcacao-grid", url: "' . Url::to(['index']) . '"});
                                    }',
                                ],
                                'bsVersion' => '5.x',
                            ]); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white" style="width: 45px;">
                                <i class="fas fa-user-md"></i>
                            </span>
                            <?= Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'userprofiles_id',
                                'data' => $userprofilesList,
                                'options' => [
                                    'placeholder' => 'Pesquisar por veterinário...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new JsExpression('function() { return "Nenhum veterinário encontrado"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var vetId = e.params.data.id;
                                        $.pjax.reload({container: "#marcacao-grid", url: "' . Url::to(['index']) . '?MarcacaoSearch[userprofiles_id]=" + vetId});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#marcacao-grid", url: "' . Url::to(['index']) . '"});
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
                                '<i class="fas fa-clock"></i> Pendentes',
                                ['index', 'MarcacaoSearch[estado]' => 'pendente'],
                                ['class' => 'btn btn-sm btn-outline-warning']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-check"></i> Realizadas',
                                ['index', 'MarcacaoSearch[estado]' => 'realizada'],
                                ['class' => 'btn btn-sm btn-outline-success']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-times"></i> Canceladas',
                                ['index', 'MarcacaoSearch[estado]' => 'cancelada'],
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
                <?php \yii\widgets\Pjax::begin(['id' => 'marcacao-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Nenhuma marcação encontrada.</div>',
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n\n{pager}",
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
                            'headerOptions' => ['style' => 'width: 80px'],
                        ],
                        [
                            'attribute' => 'data',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Data',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<strong>' . Yii::$app->formatter->asDate($model->data, 'php:d/m/Y') . '</strong>';
                            },
                            'filter' => false,
//                            'filter' => Html::activeInput('date', $searchModel, 'data', [
//                                'class' => 'form-control form-control-sm',
//                                'style' => 'width: 150px;'
//                            ]),
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'horainicio',
                            'label' => 'Hora Início',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<i class="far fa-clock"></i> ' . date('H:i', strtotime($model->horainicio));
                            },
                            'filter' => false,
//                            'filter' => Html::activeInput('time', $searchModel, 'horainicio', [
//                                'class' => 'form-control form-control-sm',
//                                'style' => 'width: 120px;'
//                            ]),
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'horafim',
                            'label' => 'Hora Fim',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<i class="far fa-clock"></i> ' . date('H:i', strtotime($model->horafim));
                            },
                            'filter' => false,
//                            'filter' => Html::activeInput('time', $searchModel, 'horafim', [
//                                'class' => 'form-control form-control-sm',
//                                'style' => 'width: 120px;'
//                            ]),
                        ],
                        [
                            'attribute' => 'animais_id',
                            'headerOptions' => ['style' => 'width: 180px'],
                            'label' => 'Animal',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->animais) {
                                    return Html::a(
                                        '<i class="fas fa-paw"></i> ' . Html::encode($model->animais->nome),
                                        ['/animal/view', 'id' => $model->animais->id],
                                        ['class' => 'text-decoration-none', 'target' => '_blank']
                                    );
                                }
                                return '-';
                            },
                            'filter' => false,
//                            'filter' => Html::activeTextInput($searchModel, 'animais_id', [
//                                'class' => 'form-control form-control-sm',
//                                'placeholder' => 'Animal...',
//                                'style' => 'width: 120px;'
//                            ]),
                        ],
                        [
                            'attribute' => 'userprofiles_id',
                            'headerOptions' => ['style' => 'width: 180px'],
                            'label' => 'Veterinário',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->userprofiles) {
                                    return Html::a(
                                        '<i class="fas fa-user-md"></i> ' . Html::encode($model->userprofiles->nomecompleto),
                                        ['/userprofile/view', 'id' => $model->userprofiles->id],
                                        ['class' => 'text-decoration-none', 'target' => '_blank']
                                    );
                                }
                                return '-';
                            },
                            'filter' => false,
//                            'filter' => Html::activeTextInput($searchModel, 'userprofiles_id', [
//                                'class' => 'form-control form-control-sm',
//                                'placeholder' => 'Veterinário...',
//                                'style' => 'width: 120px;'
//                            ]),
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'estado',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                $badges = [
                                    Marcacao::ESTADO_PENDENTE => '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pendente</span>',
                                    Marcacao::ESTADO_REALIZADA => '<span class="badge bg-success"><i class="fas fa-check"></i> Realizada</span>',
                                    Marcacao::ESTADO_CANCELADA => '<span class="badge bg-danger"><i class="fas fa-times"></i> Cancelada</span>',
                                ];
                                return $badges[$model->estado] ?? $model->estado;
                            },
                            'filter' => false,
//                            'filter' => Html::activeDropDownList($searchModel, 'estado',
//                                ['' => 'Todos...', 'pendente' => 'Pendente', 'realizada' => 'Realizada', 'cancelada' => 'Cancelada'],
//                                [
//                                    'class' => 'form-control form-control-sm',
//                                    'style' => 'width: 120px;'
//                                ]
//                            ),
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
                                            'data-confirm' => 'Tem a certeza que deseja eliminar esta marcação?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Marcacao $model, $key, $index, $column) {
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

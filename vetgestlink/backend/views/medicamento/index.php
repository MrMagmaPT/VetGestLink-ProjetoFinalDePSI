<?php
use common\models\Medicamento;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\SmallCardWidget;
use backend\widgets\PageHeaderWidget;

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

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Medicamentos',
    'icon' => 'fa-pills text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Medicamentos',
        ],
    ],
]);
?>

<div class="content">
        <div class="container-fluid">
            <!-- Card de Estatísticas -->
            <div class="row mb-4">
                <?= SmallCardWidget::widget([
                    'icon' => 'fa-pills',
                    'iconColorClass' => 'icon-blue',
                    'text' => 'Total de Medicamentos',
                    'value' => $totalCount,
                    'url' => '/medicamento/index',
                ]) ?>
                
                <?= SmallCardWidget::widget([
                    'icon' => 'fa-check-circle',
                    'iconColorClass' => 'icon-green',
                    'text' => 'Stock Bom',
                    'value' => $stockBom,
                    'url' => '/medicamento/index',
                ]) ?>
                
                <?= SmallCardWidget::widget([
                    'icon' => 'fa-exclamation-triangle',
                    'iconColorClass' => 'icon-orange',
                    'text' => 'Stock Baixo',
                    'value' => $stockBaixo,
                    'url' => '/medicamento/index',
                ]) ?>
                
                <?= SmallCardWidget::widget([
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
                        <?php if (Yii::$app->user->can('createMedication')): ?>
                            <?= Html::a(
                                '<i class="fas fa-plus"></i> Novo Medicamento',
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
                                    <i class="fas fa-pills"></i>
                                </span>
                                <?= Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'nome',
                                    'data' => $searchModel->getNomesList(),
                                    'options' => [
                                        'placeholder' => 'Pesquisar por nome...',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'language' => [
                                            'noResults' => new \yii\web\JsExpression('function() { return "Nenhum medicamento encontrado"; }'),
                                        ],
                                    ],
                                    'pluginEvents' => [
                                        'select2:select' => 'function(e) { 
                                            var nome = e.params.data.id;
                                            $.pjax.reload({container: "#medicamento-grid", url: "' . Url::to(['index']) . '?MedicamentoSearch[nome]=" + nome});
                                        }',
                                        'select2:clear' => 'function(e) {
                                            $.pjax.reload({container: "#medicamento-grid", url: "' . Url::to(['index']) . '"});
                                        }',
                                    ],
                                    'bsVersion' => '5.x',
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-info text-white" style="width: 45px;">
                                    <i class="fas fa-folder"></i>
                                </span>
                                <?= Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'categorias_id',
                                    'data' => $searchModel->getCategoriasAtivasList(),
                                    'options' => [
                                        'placeholder' => 'Pesquisar por categoria...',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'language' => [
                                            'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma categoria encontrada"; }'),
                                        ],
                                    ],
                                    'pluginEvents' => [
                                        'select2:select' => 'function(e) { 
                                            var id = e.params.data.id;
                                            $.pjax.reload({container: "#medicamento-grid", url: "' . Url::to(['index']) . '?MedicamentoSearch[categorias_id]=" + id});
                                        }',
                                        'select2:clear' => 'function(e) {
                                            $.pjax.reload({container: "#medicamento-grid", url: "' . Url::to(['index']) . '"});
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
                                    '<i class="fas fa-list"></i> Todos',
                                    ['index'],
                                    ['class' => 'btn btn-sm btn-outline-secondary']
                                ) ?>
                                <?= Html::a(
                                    '<i class="fas fa-check"></i> Ativos',
                                    ['index', 'MedicamentoSearch[eliminado]' => 0],
                                    ['class' => 'btn btn-sm btn-outline-success']
                                ) ?>
                                <?= Html::a(
                                    '<i class="fas fa-times"></i> Eliminados',
                                    ['index', 'MedicamentoSearch[eliminado]' => 1],
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
                    <?php \yii\widgets\Pjax::begin(['id' => 'medicamento-grid']); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'summary' => ' <b>Mostrando {begin} - {end}</b>',
                        'layout' => "<div class='text-center'>{summary}</div>\n{items}\n\n{pager}",
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
                                'filter' => false
                            ],
                            [
                                'attribute' => 'descricao',
                                'label' => 'Descrição',
                                'value' => function($model) {
                                    return strlen($model->descricao) > 50 
                                        ? substr($model->descricao, 0, 50) . '...' 
                                        : $model->descricao;
                                },
                                'filter' => false
                            ],
                            [
                                'attribute' => 'preco',
                                'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center'],
                                'label' => 'Preço',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return '<strong>' . number_format($model->preco, 2, ',', '.') . ' €</strong>';
                                },
                                'filter' => false
                            ],
                            [
                                'attribute' => 'quantidade',
                                'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center'],
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
                                'filter' => false
                            ],
                            [
                                'headerOptions' => ['style' => 'width: 180px'],
                                'attribute' => 'categorias_id',
                                'label' => 'Categoria',
                                'value' => function($model) {
                                    return $model->categoriaNome ?: '-';
                                },
                                'filter' => false,
                            ],
                            [
                                'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center'],
                                'attribute' => 'eliminado',
                                'label' => 'Estado',
                                'format' => 'raw',
                                'value' => function($model) {
                                    if ($model->eliminado == 1) {
                                        return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminado</span>';
                                    }
                                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>';
                                },
                                'filter' => false,
                                // 'filter' => Select2::widget([
                                //     'model' => $searchModel,
                                //     'attribute' => 'eliminado',
                                //     'data' => [
                                //         0 => 'Ativo',
                                //         1 => 'Eliminado',
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
                                        if (!Yii::$app->user->can('updateMedication')) {
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
                                        if (!Yii::$app->user->can('deleteMedication')) {
                                            return '';
                                        }
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
                    <?php \yii\widgets\Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

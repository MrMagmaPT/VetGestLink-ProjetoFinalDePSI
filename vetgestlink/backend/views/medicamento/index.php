<?php
use common\models\Medicamento;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;
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
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Novo Medicamento',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Barra de Pesquisa com Select2 -->
                <div class="row mb-3">
                    <div class="card-body">
                            <div class="col-md-6 d-flex gap-3" style="padding-bottom: 12px; padding-top: 8px;">
                                <div style="flex:1;">
                                    <?= Select2::widget([
                                        'name' => 'search_nome',
                                        'data' => $searchModel->getNomesList(),
                                        'value' => $searchModel->nome,
                                        'options' => [
                                            'placeholder' => 'Pesquisar medicação...',
                                            'id' => 'medicamento-search-nome',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'language' => [
                                                'noResults' => new \yii\web\JsExpression('function() { return "Nenhum medicamento encontrado"; }'),
                                            ],
                                        ],
                                        'bsVersion' => '5.x',
                                        'pluginEvents' => [
                                            'select2:select' => 'function(e) { 
                                                var nome = e.params.data.id;
                                                window.location.href = "' . Url::to(['index']) . '?MedicamentoSearch[nome]=" + nome;
                                            }',
                                            'select2:clear' => 'function(e) {
                                                window.location.href = "' . Url::to(['index']) . '";
                                            }',
                                        ],
                                    ]); ?>
                                </div>
                                <!--Categoria Barra de Pesquisa-->
                                <div style="flex:1;">
                                    <?= Select2::widget([
                                        'name' => 'search_categoria',
                                        'data' => $searchModel->getCategoriasAtivasList(),
                                        'value' => $searchModel->categorias_id,
                                        'options' => [
                                            'placeholder' => 'Pesquisar categoria...',
                                            'id' => 'medicamento-search-categoria',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'language' => [
                                                'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma categoria encontrada"; }'),
                                            ],
                                        ],
                                        'bsVersion' => '5.x',
                                        'pluginEvents' => [
                                            'select2:select' => 'function(e) { 
                                                var id = e.params.data.id;
                                                window.location.href = "' . Url::to(['index']) . '?MedicamentoSearch[categorias_id]=" + id;
                                            }',
                                            'select2:clear' => 'function(e) {
                                                window.location.href = "' . Url::to(['index']) . '";
                                            }',
                                        ],
                                    ]); ?>
                                </div>
                            </div>
                    </div>
                <?php \yii\widgets\Pjax::begin(['id' => 'medicamento-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n\n{pager}",
                    //Mudar a mensagem quando não houver resultados
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Não foi encontrado.</div>',
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
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
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'categorias_id',
                            'label' => 'Categoria',
                            'value' => function($model) {
                                $categoria = $model->getCategorias()->one();
                                return $categoria ? $categoria->nome : '-';
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
                            'filter' => Select2::widget([
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

<?php

use common\models\Servico;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;
use backend\widgets\SmallCardWidget;
use yii\widgets\Pjax;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\ServicoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Serviços';
$this->params['breadcrumbs'][] = 'Serviços';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Serviços',
    'icon' => 'fa-concierge-bell text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Serviços',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => 'fa-concierge-bell',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Serviços',
                'value' => $totalCount,
                'url' => '/servico/index',
            ]) ?>
            <?= SmallCardWidget::widget([
                'icon' => 'fa-concierge-bell',
                'iconColorClass' => 'icon-red',
                'text' => 'Eliminados',
                'value' => $deletedCount,
                'url' => '/servico/index',
            ]) ?>
            <?= SmallCardWidget::widget([
                'icon' => 'fa-euro-sign',
                'iconColorClass' => 'icon-green',
                'text' => 'Valor Médio',
                'value' => $avgValue !== null ? number_format($avgValue, 2) . '€' : 'N/A',
                'url' => '/servico/index',
            ]) ?>

        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Serviços
                    </h5>
                    <?php if (Yii::$app->user->can('createService')): ?>
                        <?= Html::a(
                            '<i class="fas fa-plus"></i> Novo Serviço',
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
                                <i class="fas fa-concierge-bell"></i>
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
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhum serviço encontrado"; }'),
                                    ],
                                ],
                                'pluginEvents' => [
                                    'select2:select' => 'function(e) { 
                                        var nome = e.params.data.id;
                                        $.pjax.reload({container: "#servico-grid", url: "' . Url::to(['index']) . '?ServicoSearch[nome]=" + encodeURIComponent(nome)});
                                    }',
                                    'select2:clear' => 'function(e) {
                                        $.pjax.reload({container: "#servico-grid", url: "' . Url::to(['index']) . '"});
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
                                ['index', 'ServicoSearch[eliminado]' => 0],
                                ['class' => 'btn btn-sm btn-outline-success']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-times"></i> Eliminados',
                                ['index', 'ServicoSearch[eliminado]' => 1],
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
                <!-- Para não recarregar a página inteira ao filtrar/paginar -->
                <?php Pjax::begin(['id' => 'servico-grid']); ?>
                <?= GridView::widget([
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n\n{pager}",
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Nenhum serviço encontrado para com esse nome.</div>', // mensagem personalizada
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 50px'],
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 120px'],
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
                            
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'valor',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<span class="badge bg-success">' . number_format($model->valor, 2) . '€</span>';
                            },
                            
                            
                            'filter' => false,
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
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
                            // 'filter' => kartik\select2\Select2::widget([
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
                                    if (!Yii::$app->user->can('updateService')) {
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
                                    if (!Yii::$app->user->can('deleteService')) {
                                        return '';
                                    }                                    if ($model->eliminado == 1) {
                                        return '';
                                    }                                    return Html::a(
                                        '<i class="fas fa-trash"></i>',
                                        $url,
                                        [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Eliminar',
                                            'data-toggle' => 'tooltip',
                                            'data-confirm' => 'Tem a certeza que deseja eliminar este serviço?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Servico $model, $key, $index, $column) {
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

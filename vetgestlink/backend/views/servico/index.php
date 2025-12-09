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
                'value' => Servico::find()->where(['eliminado' => 0])->count(),
                'url' => '/servico/index',
            ]) ?>
            <?= SmallCardWidget::widget([
                'icon' => 'fa-concierge-bell',
                'iconColorClass' => 'icon-gray',
                'text' => 'Eliminados',
                'value' => Servico::find()->where(['eliminado' => 1])->count(),
                'url' => '/servico/index',
            ]) ?>
            <?= BigCardWidget::widget([
                'icon' => 'fa-euro-sign',
                'iconColorClass' => 'icon-green',
                'text' => 'Valor Médio',
                'value' => number_format(Servico::find()->where(['eliminado' => 0])->average('valor'), 2) . '€',
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
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Novo Serviço',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
                <div class="row mb-3 mt-3">
                    <div class="col-md-6">
                    <!-- Barra de pesquisa Select2 para Serviço -->
                    <?= kartik\select2\Select2::widget([
                        'name' => 'search_servico',
                        'data' => $searchModel::getServicosList(),
                        'value' => $searchModel->nome,
                        'options' => [
                            'placeholder' => 'Pesquisar serviço...',
                            'id' => 'servico-search',
                            'class' => 'form-control form-control-sm',
                            'style' => 'max-width: 50px; font-size: 0.7rem; display: inline-block; padding: 1px 2px;',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'noResults' => new \yii\web\JsExpression('function() { return "Nenhum serviço encontrado"; }'),
                            ],
                            'templateResult' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                        ],
                        'bsVersion' => '5.x',
                        'pluginEvents' => [
                            'select2:select' => 'function(e) { 
                                var nome = e.params.data.id;
                                window.location.href = "' . Url::to(['index']) . '?ServicoSearch[nome]=" + encodeURIComponent(nome);
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
                            'attribute' => 'valor',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<span class="badge bg-success">' . number_format($model->valor, 2) . '€</span>';
                            },
                            'headerOptions' => ['style' => 'width: 120px'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'filter' => false,
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 120px'],
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

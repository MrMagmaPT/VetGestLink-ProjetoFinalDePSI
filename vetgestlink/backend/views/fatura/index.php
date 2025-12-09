<?php

use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\PageHeaderWidget;

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
                <!-- Barra de pesquisa com Select2 -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <?php if (method_exists($searchModel, 'getFaturasList')): ?>
                        <?= kartik\select2\Select2::widget([
                            'name' => 'search_fatura',
                            'data' => $searchModel->getFaturasList(),
                            'value' => $searchModel->id,
                            'options' => [
                                'placeholder' => 'Pesquisar fatura...',
                                'id' => 'fatura-search',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'language' => [
                                    'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma fatura encontrada"; }'),
                                ],
                                'templateResult' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                                'templateSelection' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                            ],
                            'bsVersion' => '5.x',
                            'pluginEvents' => [
                                'select2:select' => 'function(e) { 
                                    var id = e.params.data.id;
                                    window.location.href = "' . Url::to(['index']) . '?FaturaSearch[id]=" + id;
                                }',
                                'select2:clear' => 'function(e) {
                                    window.location.href = "' . Url::to(['index']) . '";
                                }',
                            ],
                        ]); ?>
                        <?php endif; ?>
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
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 80px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                        [
                            'attribute' => 'id',
                            'label' => 'Nº Fatura',
                            'headerOptions' => ['style' => 'width: 100px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'filter' => isset($searchModel) && method_exists($searchModel, 'getFaturasList')
                                ? kartik\select2\Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'id',
                                    'data' => $searchModel->getFaturasList(),
                                    'options' => [
                                        'placeholder' => 'Fatura...',
                                        'allowClear' => true,
                                        'style' => 'width: 120px;',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'language' => [
                                            'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma fatura encontrada"; }'),
                                        ],
                                    ],
                                    'bsVersion' => '5.x',
                                ]) : null,
                        ],
                        [
                            'attribute' => 'total',
                            'headerOptions' => ['style' => 'width: 120px; text-align: right'],
                            'contentOptions' => ['style' => 'text-align: right'],
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'Data',
                            'headerOptions' => ['style' => 'width: 150px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'format' => ['datetime', 'php:d/m/Y H:i'],
                        ],
                        [
                            'attribute' => 'estado',
                            'headerOptions' => ['style' => 'width: 120px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'filter' => kartik\select2\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'estado',
                                'data' => [0 => 'Pendente', 1 => 'Paga'],
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
                            'attribute' => 'metodospagamentos_id',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Método de Pagamento',
                            'value' => function($model) {
                                return $model->metodospagamentos ? $model->metodospagamentos->nome : '-';
                            },
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

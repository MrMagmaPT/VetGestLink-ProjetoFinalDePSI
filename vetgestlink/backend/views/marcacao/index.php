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
            <div class="card-body p-0">
                <?php \yii\widgets\Pjax::begin(['id' => 'marcacao-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n\n{pager}",
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 180px'],
                        ],
                        [
                            'attribute' => 'data',
                            'headerOptions' => ['style' => 'width: 180px'],
                            'label' => 'Data',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<strong>' . Yii::$app->formatter->asDate($model->data, 'php:d/m/Y') . '</strong>';
                            },
                        ],
                        [
                            'attribute' => 'horainicio',
                            'headerOptions' => ['style' => 'width: 180px'],
                            'label' => 'Hora Início',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<i class="far fa-clock"></i> ' . date('H:i', strtotime($model->horainicio));
                            },
                        ],
                        [
                            'attribute' => 'horafim',
                            'headerOptions' => ['style' => 'width: 180px'],
                            'label' => 'Hora Fim',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<i class="far fa-clock"></i> ' . date('H:i', strtotime($model->horafim));
                            },
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
                            'filter' => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'animais_id',
                                'data' => $animaisList,
                                'options' => [
                                    'placeholder' => 'Animal...',
                                    'allowClear' => true,
                                    'style' => 'width: 120px;',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumResultsForSearch' => 0,
                                    'language' => [
                                        'noResults' => new JsExpression('function() { return "Nenhum animal encontrado"; }'),
                                    ],
                                ],
                                'bsVersion' => '5.x',
                            ]),
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
                            'filter' => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'userprofiles_id',
                                'data' => $userprofilesList,
                                'options' => [
                                    'placeholder' => 'Veterinário...',
                                    'allowClear' => true,
                                    'style' => 'width: 120px;',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumResultsForSearch' => 0,
                                    'language' => [
                                        'noResults' => new JsExpression('function() { return "Nenhum veterinário encontrado"; }'),
                                    ],
                                ],
                                'bsVersion' => '5.x',
                            ]),
                        ],
                        [
                            'attribute' => 'estado',
                            'headerOptions' => ['style' => 'width: 180px'],
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
                            'filter' => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'estado',
                                'data' => [
                                    Marcacao::ESTADO_PENDENTE => 'Pendente',
                                    Marcacao::ESTADO_REALIZADA => 'Realizada',
                                    Marcacao::ESTADO_CANCELADA => 'Cancelada',
                                ],
                                'options' => [
                                    'placeholder' => 'Estado...',
                                    'allowClear' => true,
                                    'style' => 'width: 120px;',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumResultsForSearch' => 0,
                                    'language' => [
                                        'noResults' => new JsExpression('function() { return "Nenhum estado encontrado"; }'),
                                    ],
                                ],
                                'bsVersion' => '5.x',
                            ]),
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

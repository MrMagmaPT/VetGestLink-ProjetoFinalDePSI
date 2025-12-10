<?php

use common\models\Animal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\SmallCardWidget;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\AnimalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $machoCount */
/** @var int $femeaCount */
/** @var int $microchipCount */

$this->title = 'Gestão de Animais';
$this->params['breadcrumbs'][] = 'Animais';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Animais',
    'icon' => 'fa-dog text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Animais',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => 'fa-paw',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Animais',
                'value' => $totalCount,
                'url' => '/animal/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-mars',
                'iconColorClass' => 'icon-primary',
                'text' => 'Machos',
                'value' => $machoCount,
                'url' => '/animal/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-venus',
                'iconColorClass' => 'icon-pink',
                'text' => 'Fêmeas',
                'value' => $femeaCount,
                'url' => '/animal/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-microchip',
                'iconColorClass' => 'icon-green',
                'text' => 'Com Microchip',
                'value' => $microchipCount,
                'url' => '/animal/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Animais
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Novo Animal',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
            </div>
            <div class="card-body">
            <div class="card-body p-0">
                <?php \yii\widgets\Pjax::begin(['id' => 'animal-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n{pager}",
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Nenhum perfil encontrado com esse nome.</div>',
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'foto',
                            'label' => 'Foto',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->getImageUrl()) {
                                    return Html::img($model->getImageUrl(), [
                                        'alt' => $model->nome,
                                        'class' => 'rounded',
                                        'style' => 'width: 40px; height: 40px; object-fit: cover;'
                                    ]);
                                }
                                return '<i class="fas fa-paw text-muted" style="font-size: 30px;"></i>';
                            },
                            'enableSorting' => false,
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'nome',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(
                                    '<strong>' . Html::encode($model->nome) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'dtanascimento',
                            'label' => 'Data Nasc.',
                            'format' => ['date', 'php:d/m/Y'],
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'peso',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->peso ? number_format($model->peso, 2, ',', '.') . ' kg' : '-';
                            },
                        ],
                        [
                            'attribute' => 'sexo',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->sexo == 'M') {
                                    return '<span class="badge bg-primary"><i class="fas fa-mars"></i> Macho</span>';
                                } elseif ($model->sexo == 'F') {
                                    return '<span class="badge" style="background-color: #e91e63;"><i class="fas fa-venus"></i> Fêmea</span>';
                                }
                                return '-';
                            },
                                'filter' => ['M' => 'Macho', 'F' => 'Fêmea'],
                        ],
                        [
                            'attribute' => 'microship',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Microchip',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->microship) {
                                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Sim</span>';
                                }
                                return '<span class="badge bg-secondary"><i class="fas fa-times"></i> Não</span>';
                            },
                                'filter' => [1 => 'Sim', 0 => 'Não'],
                        ],
                        [
                            'attribute' => 'especies_id',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Espécie',
                            'value' => function($model) {
                                return $model->especies->nome ?? '-';
                            },
                                'filter' => Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'especies_id',
                                    'data' => $especiesList,
                                    'options' => [
                                        'placeholder' => 'Espécie...',
                                        'allowClear' => true,
                                        'style' => 'width: 120px;',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'language' => [
                                            'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma espécie encontrada"; }'),
                                        ],
                                    ],
                                    'bsVersion' => '5.x',
                                ]),
                            ],
                        [
                            'attribute' => 'userprofiles_id',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Dono',
                            'value' => function($model) {
                                return $model->userprofiles->nomecompleto ?? '-';
                            },
                                'filter' => Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'userprofiles_id',
                                    'data' => \backend\models\AnimalSearch::getActiveOwnersList(),
                                    'options' => [
                                        'placeholder' => 'Dono...',
                                        'allowClear' => true,
                                        'style' => 'width: 120px;',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'language' => [
                                            'noResults' => new \yii\web\JsExpression('function() { return "Nenhum dono encontrado"; }'),
                                        ],
                                    ],
                                    'bsVersion' => '5.x',
                                ]),
                            ],
                        [
                            'attribute' => 'eliminado',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->eliminado == 1) {
                                    return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminado</span>';
                                }
                                return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>';
                            },
                                'filter' => [0 => 'Ativo', 1 => 'Eliminado'],
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
                                            'data-confirm' => 'Tem a certeza que deseja eliminar este animal?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Animal $model, $key, $index, $column) {
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

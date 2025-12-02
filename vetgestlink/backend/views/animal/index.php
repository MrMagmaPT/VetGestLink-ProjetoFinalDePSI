<?php

use common\models\Animal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;

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

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-paw text-primary"></i>
                    Animais
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item active">Animais</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <?= BigCardWidget::widget([
                'icon' => 'fa-paw',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Animais',
                'value' => $totalCount,
                'url' => '/animal/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-mars',
                'iconColorClass' => 'icon-primary',
                'text' => 'Machos',
                'value' => $machoCount,
                'url' => '/animal/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-venus',
                'iconColorClass' => 'icon-pink',
                'text' => 'Fêmeas',
                'value' => $femeaCount,
                'url' => '/animal/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
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
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 50px'],
                        ],
                        [
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
                            'headerOptions' => ['style' => 'width: 80px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'enableSorting' => false,
                        ],
                        [
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
                            'attribute' => 'dtanascimento',
                            'label' => 'Data Nasc.',
                            'format' => ['date', 'php:d/m/Y'],
                            'headerOptions' => ['style' => 'width: 120px'],
                        ],
                        [
                            'attribute' => 'peso',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->peso ? number_format($model->peso, 2, ',', '.') . ' kg' : '-';
                            },
                            'headerOptions' => ['style' => 'width: 100px'],
                            'contentOptions' => ['style' => 'text-align: right'],
                        ],
                        [
                            'attribute' => 'sexo',
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
                            'headerOptions' => ['style' => 'width: 100px'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                        [
                            'attribute' => 'microship',
                            'label' => 'Microchip',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->microship) {
                                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Sim</span>';
                                }
                                return '<span class="badge bg-secondary"><i class="fas fa-times"></i> Não</span>';
                            },
                            'filter' => [0 => 'Não', 1 => 'Sim'],
                            'headerOptions' => ['style' => 'width: 100px'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                        [
                            'attribute' => 'especies_id',
                            'label' => 'Espécie',
                            'value' => function($model) {
                                return $model->especies->nome ?? '-';
                            },
                            'headerOptions' => ['style' => 'width: 120px'],
                        ],
                        [
                            'attribute' => 'userprofiles_id',
                            'label' => 'Dono',
                            'value' => function($model) {
                                return $model->userprofiles->nomecompleto ?? '-';
                            },
                            'headerOptions' => ['style' => 'width: 180px'],
                        ],
                        [
                            'attribute' => 'eliminado',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->eliminado == 1) {
                                    return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminado</span>';
                                }
                                return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>';
                            },
                            'filter' => [
                                0 => 'Ativo',
                                1 => 'Eliminado',
                            ],
                            'headerOptions' => ['style' => 'width: 100px'],
                            'contentOptions' => ['style' => 'text-align: center'],
                        ],
                        [
                            'class' => ActionColumn::class,
                            'header' => 'Ações',
                            'template' => '{view} {update} {delete}',
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
            </div>
        </div>
    </div>
</div>

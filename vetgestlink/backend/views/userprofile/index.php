<?php

use common\models\Userprofile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;

/** @var yii\web\View $this */
/** @var backend\models\UserprofileSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $activeCount */
/** @var int $deletedCount */
/** @var int $recentCount */

$this->title = 'Gestão de Perfis de Utilizador';
$this->params['breadcrumbs'][] = 'Perfis';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-users text-primary"></i>
                    Perfis de Utilizador
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item active">Perfis</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Card de Estatísticas -->
        <div class="row mb-4">
            <?= BigCardWidget::widget([
                'icon' => 'fa-users',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Perfis',
                'value' => $totalCount,
                'url' => '/userprofile/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-user-check',
                'iconColorClass' => 'icon-green',
                'text' => 'Perfis Ativos',
                'value' => $activeCount,
                'url' => '/userprofile/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-user-times',
                'iconColorClass' => 'icon-yellow',
                'text' => 'Perfis Eliminados',
                'value' => $deletedCount,
                'url' => '/userprofile/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-user-plus',
                'iconColorClass' => 'icon-purple',
                'text' => 'Novos (30 dias)',
                'value' => $recentCount,
                'url' => '/userprofile/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Perfis
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Novo Perfil',
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
                                        'alt' => $model->nomecompleto,
                                        'class' => 'rounded-circle',
                                        'style' => 'width: 40px; height: 40px; object-fit: cover;'
                                    ]);
                                }
                                return '<i class="fas fa-user-circle text-muted" style="font-size: 40px;"></i>';
                            },
                            'headerOptions' => ['style' => 'width: 80px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'enableSorting' => false,
                        ],
                        [
                            'attribute' => 'nomecompleto',
                            'label' => 'Nome Completo',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(
                                    '<strong>' . Html::encode($model->nomecompleto) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                        ],
                        [
                            'attribute' => 'nif',
                            'label' => 'NIF',
                            'headerOptions' => ['style' => 'width: 120px'],
                        ],
                        [
                            'attribute' => 'telemovel',
                            'label' => 'Telemóvel',
                            'headerOptions' => ['style' => 'width: 120px'],
                        ],
                        [
                            'attribute' => 'morada_cidade',
                            'label' => 'Cidade',
                            'value' => function($model) {
                                $morada = $model->getMoradas()->one();
                                return $morada ? $morada->cidade : '-';
                            },
                            'headerOptions' => ['style' => 'width: 150px'],
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
                            'headerOptions' => ['style' => 'width: 120px'],
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
                                            'data-confirm' => 'Tem a certeza que deseja eliminar este perfil?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Userprofile $model, $key, $index, $column) {
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



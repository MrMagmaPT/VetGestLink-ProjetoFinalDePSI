<?php

use common\models\Especie;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\BigCardWidget;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\EspecieSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Espécies';
$this->params['breadcrumbs'][] = 'Espécies';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Espécies',
    'icon' => 'fa-paw text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Espécies',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <?= BigCardWidget::widget([
                'icon' => 'fa-paw',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Espécies',
                'value' => Especie::find()->where(['eliminado' => 0])->count(),
                'url' => '/especie/index',
            ]) ?>
            <?= BigCardWidget::widget([
                'icon' => 'fa-paw',
                'iconColorClass' => 'icon-red',
                'text' => 'Eliminadas',
                'value' => Especie::find()->where(['eliminado' => 1])->count(),
                'url' => '/especie/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Espécies
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Nova Espécie',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
            </div>
            <div class="card-body p-0">
                <?php \yii\widgets\Pjax::begin(['id' => 'especie-grid']); ?>
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
                            'attribute' => 'nome',
                            'headerOptions' => ['style' => 'width: 180px'],
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
                            'attribute' => 'eliminado',
                            'headerOptions' => ['style' => 'width: 180px'],
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->eliminado == 1) {
                                    return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminada</span>';
                                }
                                return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativa</span>';
                            },
                            'filter' => [
                                0 => 'Ativa',
                                1 => 'Eliminada',
                            ],
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
                                            'data-confirm' => 'Tem a certeza que deseja eliminar esta espécie?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, Especie $model, $key, $index, $column) {
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

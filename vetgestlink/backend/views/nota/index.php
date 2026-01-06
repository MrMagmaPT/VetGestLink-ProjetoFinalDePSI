<?php

use common\models\Nota;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\widgets\SmallCardWidget;
use backend\widgets\BigCardWidget;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var backend\models\NotaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalCount */
/** @var int $recentCount */

$this->title = 'Gestão de Notas';
$this->params['breadcrumbs'][] = 'Notas';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Notas',
    'icon' => 'fa-sticky-note text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Notas',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Card de Estatísticas -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => 'fa-sticky-note',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Notas',
                'value' => $totalCount,
                'url' => '/nota/index',
            ]) ?>
            
            <?= BigCardWidget::widget([
                'icon' => 'fa-clock',
                'iconColorClass' => 'icon-green',
                'text' => 'Notas Recentes (7 dias)',
                'value' => $recentCount,
                'url' => '/nota/index',
            ]) ?>
        </div>

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Notas
                    </h5>
                    <?= Html::a(
                        '<i class="fas fa-plus"></i> Criar Nota',
                        ['create'],
                        ['class' => 'btn btn-success']
                    ) ?>
                </div>
            </div>
            <div class="card-body p-0">
                <?php Pjax::begin(['id' => 'nota-grid']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => ' <b>Mostrando {begin} - {end}</b>',
                    'layout' => "<div class='text-center'>{summary}</div>\n{items}\n\n{pager}",
                    'emptyText' => '<div class="alert alert-warning text-center mb-0">Não foi encontrada nenhuma nota.</div>',
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
                            'headerOptions' => ['style' => 'width: 50px'],
                        ],
                        [
                            'attribute' => 'nota',
                            'label' => 'Nota',
                            'format' => 'raw',
                            'value' => function($model) {
                                $preview = mb_strimwidth($model->nota, 0, 100, '...');
                                return Html::a(
                                    '<strong>' . Html::encode($preview) . '</strong>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'text-decoration-none']
                                );
                            },
                        ],
                        [
                            'attribute' => 'animais_id',
                            'label' => 'Animal',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->animais ? '<i class="fas fa-paw"></i> ' . Html::encode($model->animais->nome) : '-';
                            },
                            'headerOptions' => ['style' => 'width: 200px'],
                        ],
                        [
                            'attribute' => 'userprofiles_id',
                            'label' => 'Autor',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->userprofiles ? '<i class="fas fa-user"></i> ' . Html::encode($model->userprofiles->nomecompleto) : '-';
                            },
                            'headerOptions' => ['style' => 'width: 200px'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'Criado em',
                            'format' => ['datetime', 'php:d/m/Y H:i'],
                            'headerOptions' => ['style' => 'width: 150px'],
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'headerOptions' => ['style' => 'width: 100px'],
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                                        'title' => 'Ver',
                                        'class' => 'btn btn-sm btn-info',
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Editar',
                                        'class' => 'btn btn-sm btn-primary',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-trash"></i>', $url, [
                                        'title' => 'Eliminar',
                                        'class' => 'btn btn-sm btn-danger',
                                        'data-confirm' => 'Tem certeza que deseja eliminar esta nota?',
                                        'data-method' => 'post',
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, Nota $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>


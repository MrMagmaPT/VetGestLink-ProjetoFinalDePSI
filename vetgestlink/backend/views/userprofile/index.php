<?php

use common\models\Userprofile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\widgets\SmallCardWidget;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\widgets\PageHeaderWidget;

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

<?php
echo PageHeaderWidget::widget([
    'title' => 'Gestão de Perfis de Utilizador',
    'icon' => 'fa-users text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Perfis',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <!-- Card de Estatísticas -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => 'fa-users',
                'iconColorClass' => 'icon-blue',
                'text' => 'Total de Perfis',
                'value' => $totalCount,
                'url' => '/userprofile/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-user-check',
                'iconColorClass' => 'icon-green',
                'text' => 'Perfis Ativos',
                'value' => $activeCount,
                'url' => '/userprofile/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-user-times',
                'iconColorClass' => 'icon-yellow',
                'text' => 'Perfis Eliminados',
                'value' => $deletedCount,
                'url' => '/userprofile/index',
            ]) ?>
            
            <?= SmallCardWidget::widget([
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
            <div class="card-body">
                <!-- Barra de Pesquisa com Select2 -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <?= Select2::widget([
                            'name' => 'search_userprofile',
                            'data' => $searchModel->getNomecompletoList(), // [id => nomecompleto]
                            'value' => $searchModel->id,
                            'options' => [
                                'placeholder' => 'Pesquisar perfil...',
                                'id' => 'userprofile-search',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'language' => [
                                    'noResults' => new \yii\web\JsExpression('function() { return "Nenhum perfil encontrado"; }'),
                                ],
                                'templateResult' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                                'templateSelection' => new \yii\web\JsExpression('function(data) { return data.text; }'),
                            ],
                            'bsVersion' => '5.x',
                            'pluginEvents' => [
                                'select2:select' => 'function(e) { 
                                    var id = e.params.data.id;
                                    window.location.href = "' . Url::to(['index']) . '?UserprofileSearch[id]=" + id;
                                }',
                                //Redireciona para a página index sem filtros ao limpar a barra de pesquisa
                                'select2:clear' => 'function(e) {
                                    window.location.href = "' . Url::to(['index']) . '";
                                }',
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php Pjax::begin(['id' => 'userprofile-grid']); ?>
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
                            'headerOptions' => ['style' => 'width: 20px; text-align: center'],
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
                                        'alt' => $model->nomecompleto,
                                        'class' => 'rounded-circle',
                                        'style' => 'width: 40px; height: 40px; object-fit: cover;'
                                    ]);
                                }
                                return '<i class="fas fa-user-circle text-muted" style="font-size: 40px;"></i>';
                            },
                            'enableSorting' => false,
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
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
                            //Já existe filtro por defeito e desativado para usar o Select2 acima la linha 96
                            'filter'=> false
                            
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'nif',
                            'label' => 'NIF',
                            'filter' => kartik\select2\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'nif',
                                'data' => $searchModel->getNifList(),
                                'options' => [
                                    'placeholder' => 'NIF...',
                                    'allowClear' => true,
                                    'style' => 'width: 120px;',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhum NIF encontrado"; }'),
                                    ],
                                ],
                                'bsVersion' => '5.x',
                            ]),
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'telemovel',
                            'label' => 'Telemóvel',
                            'filter' => kartik\select2\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'telemovel',
                                'data' => $searchModel->getTelemovelList(),
                                'options' => [
                                    'placeholder' => 'Telemóvel...',
                                    'allowClear' => true,
                                    'style' => 'width: 120px;',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhum telemóvel encontrado"; }'),
                                    ],
                                ],
                                'bsVersion' => '5.x',
                            ]),
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 180px; text-align: center'],
                            'contentOptions' => ['style' => 'text-align: center'],
                            'attribute' => 'morada_cidade',
                            'label' => 'Cidade',
                            'value' => function($model) {
                                return $model->moradaCidade ?: '-';
                            },
                            'filter' => kartik\select2\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'morada_cidade',
                                'data' => $searchModel->getCidadeList(),
                                'options' => [
                                    'placeholder' => 'Cidade...',
                                    'allowClear' => true,
                                    'style' => 'width: 120px;',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'language' => [
                                        'noResults' => new \yii\web\JsExpression('function() { return "Nenhuma cidade encontrada"; }'),
                                    ],
                                ],
                                'bsVersion' => '5.x',
                            ]),
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
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
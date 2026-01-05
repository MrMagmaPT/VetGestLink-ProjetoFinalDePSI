<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\widgets\SmallCardWidget;

/** @var yii\web\View $this */
/** @var common\models\Especie $model */

$this->title = 'Visualizar Espécie';
$this->params['breadcrumbs'][] = ['label' => 'Espécies', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Visualizar';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-paw text-primary"></i>
                    Visualizar Espécie
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Espécies', ['index']) ?></li>
                    <li class="breadcrumb-item active">Visualizar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-paw"></i> Dados da Espécie</h5>
                    </div>
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-borderless mb-0'],
                            'attributes' => [
                                [
                                    'attribute' => 'nome',
                                    'value' => function ($model) {
                                        return '<strong>' . Html::encode($model->nome) . '</strong>';
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'eliminado',
                                    'label' => 'Estado',
                                    'value' => function ($model) {
                                        if ($model->eliminado == 1) {
                                            return '<span class="badge bg-danger"><i class="fas fa-times"></i> Eliminada</span>';
                                        }
                                        return '<span class="badge bg-success"><i class="fas fa-check"></i> Ativa</span>';
                                    },
                                    'format' => 'raw',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>

                <div class="mb-3">
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cog text-secondary"></i>
                            Ações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?= Html::a(
                                '<i class="fas fa-edit"></i> Editar',
                                ['update', 'id' => $model->id],
                                ['class' => 'btn btn-primary btn-md']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-list"></i> Ver Todos',
                                ['index'],
                                ['class' => 'btn btn-secondary btn-md']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-trash"></i> Eliminar',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-md',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar este animal?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

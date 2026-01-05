<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */

$this->title = 'Visualizar Nota: ' . $model->animais->nome;
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Visualizar';
\yii\web\YiiAsset::register($this);
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-paw text-primary"></i>
                    Visualizar Nota <?= Html::encode(($model->animais->nome)) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Notas', ['index']) ?></li>
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
                        <h5 class="mb-0"><i class="fas fa-paw"></i> Dados da Nota</h5>
                    </div>
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-borderless mb-0'],
                            'attributes' => [
                                'created_at',
                                [
                                    'attribute' => 'userprofiles_id',
                                    'label' => 'Utilizador',
                                    'value' => function ($model) {
                                        return '<strong>' . Html::encode($model->userprofiles->nomecompleto) . '</strong>';
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'animais_id',
                                    'label' => 'Animal',
                                    'value' => function ($model) {
                                        return Html::encode($model->animais->nome);
                                    },
                                    'format' => 'text',
                                ],
                                [
                                    'attribute' => 'nota',
                                    'value' => function ($model) {
                                        return '<strong>' . Html::encode($model->nota) . '</strong>';
                                    },
                                    'format' => 'raw',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>

                <div class="mb-3">
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['animal/view', 'id' => $model->animais_id], ['class' => 'btn btn-secondary']) ?>
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
                                '<i class="fas fa-trash"></i> Eliminar',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-md',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar esta nota?',
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

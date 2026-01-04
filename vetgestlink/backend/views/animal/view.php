<?php

use yii\helpers\Html;
use backend\widgets\SmallCardWidget;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = 'Animal: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Animais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nome;
\yii\web\YiiAsset::register($this);
$this->registerCssFile('@web/static/css/view.css');
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-paw text-primary"></i>
                    <?= Html::encode($model->nome) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Animais', ['index']) ?></li>
                    <li class="breadcrumb-item active"><?= Html::encode($model->nome) ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de Resumo -->
        <div class="row mb-4">
            <?= SmallCardWidget::widget([
                'icon' => $model->sexo == 'M' ? 'fa-mars' : 'fa-venus',
                'iconColorClass' => $model->sexo == 'M' ? 'icon-blue' : 'icon-pink',
                'text' => 'Sexo',
                'value' => $model->sexo == 'M' ? 'Macho' : 'Fêmea',
                'url' => '#',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-weight',
                'iconColorClass' => 'icon-green',
                'text' => 'Peso',
                'value' => $model->peso ? number_format($model->peso, 2, ',', '.') . ' kg' : 'N/A',
                'url' => '#',
            ]) ?>
            
            <?= SmallCardWidget::widget([
                'icon' => 'fa-microchip',
                'iconColorClass' => $model->microship ? 'icon-success' : 'icon-secondary',
                'text' => 'Microchip',
                'value' => $model->microship ? 'Sim' : 'Não',
                'url' => '#',
            ]) ?>

            <?= SmallCardWidget::widget([
                'icon' => 'fa-birthday-cake',
                'iconColorClass' => 'icon-purple',
                'text' => 'Idade',
                'value' => $model->getIdadeExtenso(),
                'url' => '#',
            ]) ?>
        </div>

        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Card Informações Básicas -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informações do Animal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-signature"></i> Nome:
                            </div>
                            <div class="col-md-9">
                                <strong><?= Html::encode($model->nome) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-calendar"></i> Data Nasc.:
                            </div>
                            <div class="col-md-9">
                                <?= $model->dtanascimento ? Yii::$app->formatter->asDate($model->dtanascimento, 'php:d/m/Y') : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-weight"></i> Peso:
                            </div>
                            <div class="col-md-9">
                                <?= $model->peso ? number_format($model->peso, 2, ',', '.') . ' kg' : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-venus-mars"></i> Sexo:
                            </div>
                            <div class="col-md-9">
                                <?php if ($model->sexo == 'M'): ?>
                                    <span class="badge bg-primary"><i class="fas fa-mars"></i> Macho</span>
                                <?php elseif ($model->sexo == 'F'): ?>
                                    <span class="badge" style="background-color: #e91e63;"><i class="fas fa-venus"></i> Fêmea</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-microchip"></i> Microchip:
                            </div>
                            <div class="col-md-9">
                                <?php if ($model->microship): ?>
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Sim</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Não</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Classificação -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tags text-info"></i>
                            Classificação
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-paw"></i> Espécie:
                            </div>
                            <div class="col-md-9">
                                <?= $model->especies ? Html::a(
                                    Html::encode($model->especies->nome),
                                    ['/especie/view', 'id' => $model->especies->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-dog"></i> Raça:
                            </div>
                            <div class="col-md-9">
                                <?= $model->racas ? Html::a(
                                    Html::encode($model->racas->nome),
                                    ['/raca/view', 'id' => $model->racas->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 fw-bold text-muted">
                                <i class="fas fa-user"></i> Dono:
                            </div>
                            <div class="col-md-9">
                                <?= $model->userprofiles ? Html::a(
                                    Html::encode($model->userprofiles->nomecompleto),
                                    ['/userprofile/view', 'id' => $model->userprofiles->id],
                                    ['class' => 'text-decoration-none']
                                ) : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Notas -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-sticky-note text-warning"></i>
                            Notas
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($model->notas)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($model->notas as $nota): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                                            <h6 class="mb-1">
                                                <i class="fas fa-note-sticky text-warning"></i>
                                                Nota #<?= $nota->id ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="far fa-clock"></i>
                                                <?= Yii::$app->formatter->asDatetime($nota->created_at) ?>
                                            </small>
                                        </div>
                                        <p class="mb-2"><?= Html::encode($nota->nota) ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i>
                                            Por: <?= Html::encode($nota->userprofiles->nomecompleto ?? 'N/A') ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i>
                                Sem notas registadas para este animal.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita - Foto e Ações -->
            <div class="col-md-4">
                <!-- Card Fotografia -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-image text-success"></i>
                            Fotografia
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if ($model->getImageUrl()): ?>
                            <img src="<?= $model->getImageUrl() ?>" 
                                 alt="<?= Html::encode($model->nome) ?>" 
                                 class="img-fluid rounded shadow-sm mb-3"
                                 style="max-height: 300px; object-fit: cover;">
                        <?php else: ?>
                            <div class="py-5">
                                <i class="fas fa-paw text-muted" style="font-size: 100px;"></i>
                                <p class="text-muted mt-3">Sem fotografia</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card Ações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cog text-secondary"></i>
                            Ações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if (Yii::$app->user->can('viewAnimals')): ?>
                                <?= Html::a(
                                    '<i class="fas fa-list"></i> Ver Todos',
                                    ['index'],
                                    ['class' => 'btn btn-secondary btn-md']
                                ) ?>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('updateAnimal')): ?>
                                <?= Html::a(
                                    '<i class="fas fa-edit"></i> Editar',
                                    ['update', 'id' => $model->id],
                                    ['class' => 'btn btn-primary btn-md']
                                ) ?>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('deleteAnimal')): ?>
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
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Card Informação -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info text-info"></i>
                            Informação
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-calendar-plus"></i>
                                <strong>Registado:</strong> <?= $model->dtanascimento ? 'desde ' . Yii::$app->formatter->asDate($model->dtanascimento, 'php:Y') : '-' ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

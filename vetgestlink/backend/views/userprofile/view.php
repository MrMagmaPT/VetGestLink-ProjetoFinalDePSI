<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */

$this->title = 'Perfil: ' . $model->nomecompleto;
$this->params['breadcrumbs'][] = ['label' => 'Perfis de Utilizador', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nomecompleto;
\yii\web\YiiAsset::register($this);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-user text-primary"></i>
                    <?= Html::encode($model->nomecompleto) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Perfis', ['index']) ?></li>
                    <li class="breadcrumb-item active"><?= Html::encode($model->nomecompleto) ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Coluna Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Card Informações Pessoais -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user text-primary"></i>
                            Informações Pessoais
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-user-circle"></i> Nome Completo:
                            </div>
                            <div class="col-md-8">
                                <?= Html::encode($model->nomecompleto) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-id-card"></i> NIF:
                            </div>
                            <div class="col-md-8">
                                <?= Html::encode($model->nif) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-phone"></i> Telemóvel:
                            </div>
                            <div class="col-md-8">
                                <?= Html::encode($model->telemovel) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold text-muted">
                                <i class="fas fa-calendar"></i> Data de Nascimento:
                            </div>
                            <div class="col-md-8">
                                <?= $model->dtanascimento ? Yii::$app->formatter->asDate($model->dtanascimento, 'php:d/m/Y') : '<span class="text-muted">Não definido</span>' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Moradas -->
                <?php if (!empty($model->moradas)): ?>
                    <?php foreach ($model->moradas as $i => $morada): ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    Morada <?= $i + 1 ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold text-muted">
                                        <i class="fas fa-road"></i> Rua:
                                    </div>
                                    <div class="col-md-9">
                                        <?= Html::encode($morada->rua) ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold text-muted">
                                        <i class="fas fa-door-open"></i> Nº Porta:
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::encode($morada->nporta) ?>
                                    </div>
                                    <div class="col-md-3 fw-bold text-muted">
                                        <i class="fas fa-building"></i> Andar:
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::encode($morada->andar ?: '-') ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold text-muted">
                                        <i class="fas fa-mail-bulk"></i> Cód. Postal:
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::encode($morada->cdpostal) ?>
                                    </div>
                                    <div class="col-md-3 fw-bold text-muted">
                                        <i class="fas fa-inbox"></i> Cx. Postal:
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::encode($morada->cxpostal ?: '-') ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold text-muted">
                                        <i class="fas fa-city"></i> Cidade:
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::encode($morada->cidade) ?>
                                    </div>
                                    <div class="col-md-3 fw-bold text-muted">
                                        <i class="fas fa-location-dot"></i> Localidade:
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::encode($morada->localidade) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Coluna Direita - Foto e Ações -->
            <div class="col-md-4">
                <!-- Card Foto de Perfil -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-camera text-success"></i>
                            Foto de Perfil
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if ($model->getImageUrl()): ?>
                            <?= Html::img($model->getImageUrl(), [
                                'alt' => $model->nomecompleto,
                                'class' => 'img-thumbnail rounded',
                                'style' => 'max-width: 100%; height: auto;'
                            ]) ?>
                        <?php else: ?>
                            <i class="fas fa-user-circle text-muted" style="font-size: 150px;"></i>
                            <p class="text-muted mt-3">Sem foto de perfil</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card Ações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs"></i>
                            Ações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?= Html::a(
                                '<i class="fas fa-edit"></i> Editar Perfil',
                                ['update', 'id' => $model->id],
                                ['class' => 'btn btn-primary btn-lg']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-list"></i> Ver Todos',
                                ['index'],
                                ['class' => 'btn btn-secondary btn-lg']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-trash"></i> Eliminar',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-lg',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar este perfil?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>

                <!-- Card Informações do Sistema -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-info"></i>
                            Informações do Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-hashtag"></i> ID do Perfil:
                            </small>
                            <div class="fw-bold"><?= Html::encode($model->id) ?></div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user-tag"></i> ID do Utilizador:
                            </small>
                            <div class="fw-bold"><?= Html::encode($model->user_id) ?></div>
                        </div>
                        <?php if ($model->getCreatedAt()): ?>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> Criado em:
                                </small>
                                <div class="fw-bold"><?= Yii::$app->formatter->asDatetime($model->getCreatedAtTimestamp(), 'php:d/m/Y H:i') ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if ($model->getUpdatedAt()): ?>
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-sync"></i> Atualizado em:
                                </small>
                                <div class="fw-bold"><?= Yii::$app->formatter->asDatetime($model->getUpdatedAtTimestamp(), 'php:d/m/Y H:i') ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



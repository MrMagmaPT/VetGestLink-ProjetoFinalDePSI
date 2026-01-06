<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var common\models\Nota $model */

$this->title = 'Nota #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nota-view">
    <div class="container py-4">

        <!-- Cabeçalho -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">
                        <i class="fas fa-sticky-note text-primary me-2"></i>
                        <?= Html::encode($this->title) ?>
                    </h2>
                    <?= Html::a('<i class="fas fa-arrow-left me-2"></i>Voltar', ['index', 'animalId' => $model->animais_id], ['class' => 'btn btn-outline-secondary shadow-sm']) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <!-- Card Principal -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-gradient bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Detalhes da Nota
                            </h5>
                            <div class="btn-group" role="group">
                                <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], [
                                    'class' => 'btn btn-sm btn-light',
                                    'title' => 'Editar'
                                ]) ?>
                                <?= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => 'Excluir',
                                    'data' => [
                                        'confirm' => 'Tem certeza que deseja excluir esta nota?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Informações -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Criado por</small>
                                            <strong><?= Html::encode($model->userprofiles->nomecompleto ?? 'Desconhecido') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar-alt fa-2x text-info me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Data de Criação</small>
                                            <strong><?= Yii::$app->formatter->asDate($model->created_at, 'php:d/m/Y') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Conteúdo da Nota -->
                        <div class="border-top pt-3">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-align-left me-2"></i>Conteúdo da Nota
                            </h6>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0"><?= nl2br(Html::encode($model->nota)) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
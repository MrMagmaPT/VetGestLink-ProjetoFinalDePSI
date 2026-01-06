<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var common\models\Lembrete $lembrete */

$this->title = 'Lembrete #' . $lembrete->id;
$this->params['breadcrumbs'][] = ['label' => 'Lembretes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="lembrete-view">
    <div class="container py-4">

        <!-- Cabeçalho -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">
                        <i class="fas fa-bell text-warning me-2"></i>
                        <?= Html::encode($this->title) ?>
                    </h2>
                    <?= Html::a('<i class="fas fa-arrow-left me-2"></i>Voltar', ['index'], ['class' => 'btn btn-outline-secondary shadow-sm']) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <!-- Card Principal -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-gradient bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Detalhes do Lembrete
                            </h5>
                            <div class="btn-group" role="group">
                                <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $lembrete->id], [
                                    'class' => 'btn btn-sm btn-light',
                                    'title' => 'Editar'
                                ]) ?>
                                <?= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id' => $lembrete->id], [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => 'Excluir',
                                    'data' => [
                                        'confirm' => 'Tem certeza que deseja excluir este lembrete?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Informações -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Criado por</small>
                                            <strong><?= Html::encode($lembrete->userprofile->nomecompleto ?? 'Desconhecido') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar-plus fa-2x text-success me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Criado em</small>
                                            <strong><?= Yii::$app->formatter->asDate($lembrete->created_at, 'php:d/m/Y') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar-check fa-2x text-info me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Atualizado em</small>
                                            <strong><?= Yii::$app->formatter->asDate($lembrete->updated_at, 'php:d/m/Y') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Conteúdo do Lembrete -->
                        <div class="border-top pt-3">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-align-left me-2"></i>Descrição
                            </h6>
                            <div class="p-3 bg-warning bg-opacity-10 rounded border border-warning">
                                <p class="mb-0"><?= nl2br(Html::encode($lembrete->descricao)) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
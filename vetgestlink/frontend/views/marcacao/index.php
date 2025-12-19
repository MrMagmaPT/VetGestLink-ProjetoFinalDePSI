<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Marcacao[] $marcacoesUsuario */

$this->title = 'Minhas Marcações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marcacao-index">
    <div class="container py-4">

        <div class="text-center mb-4">
            <h1 class="mb-0">
                <i class="fas fa-calendar-check text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h1>
        </div>

        <?php if (empty($marcacoesUsuario)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i>
                Nenhuma marcação encontrada.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($marcacoesUsuario as $index => $marcacao): ?>
                    <?php
                    // Determinar cor do badge baseado no estado
                    $estadoColors = [
                        'pendente' => 'warning',
                        'confirmada' => 'info',
                        'concluída' => 'success',
                        'cancelada' => 'danger',
                    ];
                    $estadoLower = strtolower($marcacao->estado);
                    $badgeColor = $estadoColors[$estadoLower] ?? 'secondary';
                    ?>
                    
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-header bg-white border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                        Marcação #<?= $marcacao->id ?>
                                    </h5>
                                    <span class="badge bg-<?= $badgeColor ?>">
                                        <?= Html::encode($marcacao->estado) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <!-- Informações Principais -->
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar text-primary me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">Data</small>
                                                <strong><?= Yii::$app->formatter->asDate($marcacao->data, 'php:d/m/Y') ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-clock text-primary me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">Horário</small>
                                                <strong><?= Html::encode($marcacao->horainicio) ?> - <?= Html::encode($marcacao->horafim) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($marcacao->animais): ?>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-paw text-primary me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">Animal</small>
                                                <strong><?= Html::encode($marcacao->animais->nome) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Diagnóstico (Colapsável com CSS) -->
                                <?php if ($marcacao->diagnostico): ?>
                                    <div class="mt-3">
                                        <input type="checkbox" id="diagnostico-toggle-<?= $index ?>" class="diagnostico-checkbox" hidden>
                                        <div class="diagnostico-content mt-2">
                                            <div class="card card-body bg-light">
                                                <strong class="mb-2 d-block">
                                                    <i class="fas fa-notes-medical text-primary"></i> Diagnóstico:
                                                </strong>
                                                <p class="mb-0"><?= nl2br(Html::encode($marcacao->diagnostico)) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-footer bg-light border-top">
                                <div class="mt-auto d-flex justify-content-center gap-2">
                                    <?= Html::a('Ver Detalhes', ['view', 'id' => $marcacao->id], [
                                        'class' => 'btn btn-dark rounded-pill'
                                    ]) ?>
                                </div>
                                <small class="text-muted d-block mt-2 text-center w-100">
                                    <i class="far fa-clock"></i>
                                    Criado em: <?= Yii::$app->formatter->asDatetime($marcacao->created_at, 'php:d/m/Y H:i') ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>




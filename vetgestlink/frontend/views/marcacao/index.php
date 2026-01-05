<?php

use yii\helpers\Html;
use \common\components\fullcalendar\FullcalendarWidget;

/** @var yii\web\View $this */
/** @var common\models\Marcacao[] $marcacoesRealizadas */
/** @var common\models\Marcacao[] $marcacoesPendentes */

$this->title = 'Histórico de Consultas';
$this->params['breadcrumbs'][] = $this->title;

$estadoColors = [
    'pendente' => 'warning',
    'realizada' => 'success',
    'cancelada' => 'danger',
];
?>
<div class="marcacao-index">
    <div class="container py-4" style="min-height: 60vh;">

        <div class="text-center mb-4">
            <h1 class="mb-0">
                <i class="fas fa-calendar-check text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h1>
        </div>

        <!-- PRÓXIMAS MARCAÇÕES -->
        <div class="mb-5">
            <h2 class="mb-3">
                <i class="fas fa-calendar-plus text-success"></i>
                Próximas Marcações
            </h2>
            
            <?php if (empty($marcacoesPendentes)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i>
                    Nenhuma marcação futura encontrada.
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Calendário -->
                    <div class="col-lg-6">
                        <div class="calendario-wrapper p-2 h-100">
                            <?= FullcalendarWidget::widget([
                                'options' => [
                                    'header' => [
                                        'left' => 'prev,next today',
                                        'center' => 'title',
                                        'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
                                    ],
                                    'lang' => 'pt-pt',
                                    'loading' => "js:function loading(bool) {if (bool) $('#loading').show();else $('#loading').hide();}",
                                    'events' => $eventos,
                                    'timeFormat' => '',
                                ],
                                'htmlOptions' => [
                                    'style' => 'margin:0 auto; width:100%; min-width:320px; max-width:600px; height:auto; min-height:220px;'
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <!-- Cards das marcações futuras -->
                    <div class="col-lg-6">
                        <div class="cards-wrapper">
                            <div class="row g-4 flex-grow-1">
                                <?php foreach ($marcacoesPendentes as $index => $marcacao): ?>
                                    <?php
                                    $estadoLower = strtolower($marcacao->estado);
                                    $badgeColor = $estadoColors[$estadoLower] ?? 'secondary';
                                    ?>
                                    <div class="col-12">
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
                                            </div>
                                            <div class="card-footer bg-light border-top">
                                                <div class="mt-auto d-flex justify-content-center gap-2">
                                                    <?= Html::a('Ver Detalhes', ['view', 'id' => $marcacao->id], [
                                                        'class' => 'btn btn-dark rounded-pill'
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- HISTÓRICO DE CONSULTAS -->
        <div>
            <h2 class="mb-3">
                <i class="fas fa-history text-info"></i>
                Histórico de Consultas
            </h2>
            
            <?php if (empty($marcacoesRealizadas)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i>
                    Nenhuma consulta anterior encontrada.
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($marcacoesRealizadas as $index => $marcacao): ?>
                        <?php
                        $estadoLower = strtolower($marcacao->estado);
                        $badgeColor = $estadoColors[$estadoLower] ?? 'secondary';
                        ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card shadow-sm h-100 border-0">
                                <div class="card-header bg-white border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-calendar-check text-info"></i>
                                            Consulta #<?= $marcacao->id ?>
                                        </h5>
                                        <span class="badge bg-<?= $badgeColor ?>">
                                            <?= Html::encode($marcacao->estado) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
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
                                    <?php if ($marcacao->diagnostico): ?>
                                        <div class="mt-3">
                                            <input type="checkbox" id="diagnostico-toggle-past-<?= $index ?>" class="diagnostico-checkbox" hidden>
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
</div>




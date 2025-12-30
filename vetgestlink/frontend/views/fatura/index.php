<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Fatura[] $faturasUsuario */

$this->title = 'Minhas Faturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faturas-index">
    <div class="container py-4" style="min-height: 60vh;">

        <div class="text-center mb-4">
            <h1 class="mb-0">
                <i class="fas fa-file-invoice-dollar text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h1>
        </div>

        <?php if (empty($faturasUsuario)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i>
                Nenhuma fatura encontrada.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($faturasUsuario as $fatura): ?>
                    <?php
                    $isPaid = (bool)$fatura->estado;
                    $estadoText = $isPaid ? 'Pago' : 'Pendente';
                    $estadoColor = $isPaid ? 'success' : 'warning';
                    $estadoIcon = $isPaid ? 'check-circle' : 'clock';
                    ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-header bg-<?= $estadoColor ?> text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>
                                        <i class="fas fa-file-invoice"></i>
                                        Fatura #<?= $fatura->id ?>
                                    </strong>
                                    <span>
                                        <i class="fas fa-<?= $estadoIcon ?>"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                
                                <!-- Total -->
                                <div class="text-center mb-3">
                                    <h3 class="text-<?= $estadoColor ?> mb-0">
                                        <?= Yii::$app->formatter->asCurrency($fatura->total, 'EUR') ?>
                                    </h3>
                                </div>

                                <!-- Informações -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">
                                            <i class="far fa-calendar"></i> Data:
                                        </span>
                                        <strong><?= Yii::$app->formatter->asDate($fatura->created_at, 'php:d/m/Y') ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">
                                            <i class="fas fa-credit-card"></i> Método:
                                        </span>
                                        <strong><?= Html::encode($fatura->metodospagamentos->nome ?? 'N/A') ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">
                                            <i class="fas fa-info-circle"></i> Estado:
                                        </span>
                                        <span class="badge bg-<?= $estadoColor ?>">
                                            <i class="fas fa-<?= $estadoIcon ?>"></i>
                                            <?= $estadoText ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="mt-auto d-grid gap-2">
                                    <?= Html::a(
                                        '<i class="fas fa-eye"></i> Ver Detalhes',
                                        ['view', 'id' => $fatura->id],
                                        ['class' => 'btn btn-primary btn-sm']
                                    ) ?>
                                    
                                    <?php if (!$isPaid): ?>
                                        <?= Html::a(
                                            '<i class="fas fa-money-bill-wave"></i> Pagar Agora',
                                            ['pagar', 'id' => $fatura->id],
                                            [
                                                'class' => 'btn btn-success btn-sm',
                                                'data-method' => 'post',
                                                'data-confirm' => 'Confirma o pagamento desta fatura?'
                                            ]
                                        ) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

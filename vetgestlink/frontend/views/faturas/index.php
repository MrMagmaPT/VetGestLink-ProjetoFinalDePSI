<?php

use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fatura';
$this->params['breadcrumbs'][] = $this->title;

$faturas = $dataProvider->getModels();
?>

<div class="faturas-index">

    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="row g-4 justify-content-center">
        <?php foreach ($faturas as $fatura): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card shadow-sm h-100 rounded-4">

                    <div class="card-body d-flex flex-column text-center">

                        <!-- ID and Total -->
                        <h5 class="card-title mb-2">Fatura #<?= $fatura->id ?></h5>
                        <p class="mb-1"><strong>Total:</strong> <?= Yii::$app->formatter->asCurrency($fatura->total, 'EUR') ?></p>

                        <!-- Date -->
                        <p class="text-muted mb-2">
                            <strong>Data:</strong> <?= Yii::$app->formatter->asDate($fatura->data, 'php:d/m/Y') ?>
                        </p>

                        <!-- Estado (boolean) -->
                        <?php
                        $isPaid = (bool)$fatura->estado;
                        $estadoText = $isPaid ? 'Pago' : 'Pendente';
                        $estadoColor = $isPaid ? 'success' : 'danger';
                        ?>
                        <span class="badge bg-<?= $estadoColor ?> mb-3"><?= $estadoText ?></span>

                        <div class="mt-auto d-flex justify-content-center gap-2">

                            <!-- Ver button -->
                            <?= Html::a('<i class="bi bi-eye"></i> Ver', ['view', 'id' => $fatura->id], [
                                'class' => 'btn '
                            ]) ?>

                            <!-- Pagar button (only if not paid) -->
                            <?php if (!$isPaid): ?>
                                <?= Html::a('<i class="bi bi-cash-stack"></i> Pagar', ['pagar', 'id' => $fatura->id], [
                                    'class' => 'btn',
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => 'Tem certeza que deseja marcar esta fatura como paga?',
                                    ],
                                ]) ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

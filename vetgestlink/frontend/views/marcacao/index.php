<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Marcações';
$this->params['breadcrumbs'][] = $this->title;

// Register CSS and JS
$this->registerCssFile('@web/static/css/custom-variables.css', ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerCssFile('@web/static/css/marcacao.css', ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerJsFile('@web/static/js/marcacao.js', ['depends' => [\yii\web\YiiAsset::class]]);
?>

<div class="marcacoes-index container py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>
    </div>

    <?php foreach ($dataProvider->models as $i => $model): ?>
        <div class="card mb-3 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-2">
                            Marcação #<?= Html::encode($model->id) ?>
                        </h5>

                        <p class="mb-1">
                            <strong>📅 Data:</strong>
                            <?= Html::encode(Yii::$app->formatter->asDate($model->data, 'php:d/m/Y')) ?>
                        </p>

                        <p class="mb-1">
                            <strong>🕒 Hora:</strong>
                            <?= Html::encode($model->horainicio) ?> -
                            <?= Html::encode($model->horafim) ?>
                        </p>

                        <p class="text-muted mb-0">
                            Criado em:
                            <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
                        </p>
                    </div>

                    <!-- Toggle button with chevron -->
                    <div class="ms-3">
                        <button class="btn btn-sm btn-outline-secondary toggle-diagnostico"
                                type="button"
                                data-target="#diagnostico-<?= $i ?>"
                                aria-expanded="false"
                                aria-controls="diagnostico-<?= $i ?>">
                            <span class="me-2">Ver diagnóstico</span>
                            <span class="chev" aria-hidden="true">▼</span>
                        </button>
                    </div>
                </div>

                <!-- Collapsible Diagnóstico (initially hidden) -->
                <div id="diagnostico-<?= $i ?>" class="collapse-custom mt-3" hidden>
                    <div class="card card-body bg-light border">
                        <strong>Diagnóstico:</strong>
                        <p class="mb-0">
                            <?= $model->diagnostico ? Html::encode($model->diagnostico) : '<em>Sem diagnóstico</em>' ?>
                        </p>
                    </div>
                </div>

            </div>

        </div>
    <?php endforeach; ?>

</div>


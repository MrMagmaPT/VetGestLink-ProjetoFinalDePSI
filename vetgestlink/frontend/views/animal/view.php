<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Animal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<div class="animais-view container py-4">

    <!-- Title -->
    <div class="text-center mb-4">
        <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>
        <p class="text-muted">Informações detalhadas do animal</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <!-- Card -->
            <div class="card shadow-lg border-0 rounded-4">

                <!-- IMAGE IF AVAILABLE -->
                <?php
                $foto = $model->hasImage() ? $model->getImageUrl() : "/img/default-animal.jpg";
                ?>
                <img src="<?= $foto ?>" alt="Foto Animal" class="card-img-top rounded-top-4">

                <div class="card-body p-4">

                    <!-- DETAILS -->
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-borderless mb-0'],
                        'attributes' => [
                            'nome',
                            [
                                'attribute' => 'dtanascimento',
                                'label' => 'Data de Nascimento',
                                'format' => ['date', 'php:d/m/Y'],
                            ],
                            [
                                'attribute' => 'peso',
                                'label' => 'Peso (Kg)',
                            ],
                            [
                                'attribute' => 'microship',
                                'label' => 'Microchip',
                                'value' => $model->microship == 0 ? 'Não' : 'Sim'
                            ],
                            [
                                'attribute' => 'sexo',
                                'value' => $model->sexo == 'M' ? 'Macho' : 'Fêmea',
                            ],
                            [
                                'attribute' => 'especies_id',
                                'label' => 'Espécie',
                                'value' => $model->especies->nome ?? 'Não definido',
                            ],
                            [
                                'attribute' => 'racas_id',
                                'label' => 'Raça',
                                'value' => $model->racas->nome ?? 'Não definido',
                            ],
                        ],
                    ]) ?>

                    <!-- NOTAS -->
                    <?php if (!empty($model->notas)): ?>
                        <div class="mt-4">
                            <h5 class="fw-bold mb-3">Notas sobre o Animal</h5>
                            <div class="list-group">
                                <?php foreach ($model->notas as $nota): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between mb-2">
                                            <h6 class="mb-0">Nota #<?= $nota->id ?></h6>
                                            <small class="text-muted"><?= Yii::$app->formatter->asDatetime($nota->created_at) ?></small>
                                        </div>
                                        <p class="mb-1"><?= nl2br(Html::encode($nota->nota)) ?></p>
                                        <small class="text-muted">Por: <?= Html::encode($nota->userprofiles->nomecompleto ?? 'N/A') ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-center mt-4">
                        <?= Html::a('Voltar', ['index'], [
                            'class' => 'btn btn-secondary'
                        ]) ?>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

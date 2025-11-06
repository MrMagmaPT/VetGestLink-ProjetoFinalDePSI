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
                $foto = $model->foto ?? "/img/default-animal.jpg";
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
                    <?php if (!empty($model->notas)) : ?>
                        <h4 class="mt-4">Notas</h4>
                        <ul>
                            <?php foreach ($model->notas as $nota): ?>
                                <li class="list-group-item">
                                    <strong><?= Yii::$app->formatter->asDate($nota->create_at) ?></strong>
                                    <br>
                                    <em><?= Html::encode($nota->userprofiles->nomecompleto ?? 'Desconhecido') ?></em>
                                    <br>
                                    <?= Html::encode($nota->nota) ?>
                                </li>

                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Sem notas</p>
                    <?php endif; ?>

                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-between mt-4">
                        <?= Html::a('Voltar', ['index'], [
                            'class' => 'btn '
                        ]) ?>

                        <?= Html::a('Nova Nota', ['animal/create-nota', 'animal_id' => $model->id], [
                            'class' => 'btn '
                        ]) ?>
                    </div>


                </div>
            </div>

        </div>
    </div>

</div>

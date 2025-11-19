<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;

$this->title = 'Meus Animais';
$this->params['breadcrumbs'][] = $this->title;

$animais = $dataProvider->getModels();
?>

<div class="animal-index">

    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="row g-4 justify-content-center">
        <?php if (empty($animais)): ?>
            <div class="col-12">
                <p class="text-center text-muted fst-italic">Nenhum animal registado.</p>
            </div>
        <?php else: ?>
            <?php foreach ($animais as $animal): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm h-100 rounded-4">

                        <!-- IMAGE -->
                        <?php
                        $foto = $animal->hasImage() ? $animal->getImageUrl() : "/img/default-animal.jpg";
                        ?>
                        <img src="<?= Html::encode($foto) ?>" class="card-img-top rounded-top-4" alt="Foto Animal" style="height: 200px; object-fit: cover;">

                        <div class="card-body text-center d-flex flex-column">

                            <!-- NAME -->
                            <h4 class="card-title mb-2"><?= Html::encode($animal->nome) ?></h4>

                            <!-- SPECIES -->
                            <p class="text-muted mb-3">
                                <?= Html::encode($animal->especies->nome ?? "Sem espécie") ?>
                            </p>

                            <div class="mt-auto d-flex justify-content-center gap-2">
                                <?= Html::a('Ver Detalhes', ['view', 'id' => $animal->id], [
                                    'class' => 'btn btn-primary btn-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>


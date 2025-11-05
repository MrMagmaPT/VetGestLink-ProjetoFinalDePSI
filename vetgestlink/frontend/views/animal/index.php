<?php

use common\models\Animal;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Animal';
$this->params['breadcrumbs'][] = $this->title;

$animais = $dataProvider->getModels();
?>

<div class="animais-index">

    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="row g-4 justify-content-center">
        <?php foreach ($animais as $animal): ?>

            <div class="col-md-4 col-lg-3">
                <div class="card shadow-sm h-100 rounded-4">

                    <!-- IMAGE -->
                    <?php
                    $foto = $animal->foto ?? "/img/default-animal.jpg";   // update if needed
                    ?>
                    <img src="<?= $foto ?>" class="card-img-top rounded-top-4" alt="Foto Animal">

                    <div class="card-body text-center d-flex flex-column">

                        <!-- NAME -->
                        <h4 class="card-title mb-2"><?= Html::encode($animal->nome) ?></h4>

                        <!-- SPECIES -->
                        <p class="text-muted mb-3">
                            <?= Html::encode($animal->especies->nome ?? "Sem espécie") ?>
                        </p>

                        <div class="mt-auto d-flex justify-content-center gap-2">

                            <?= Html::a('Ver', ['view', 'id' => $animal->id], [
                                'class' => 'btn '
                            ]) ?>

                            </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

</div>

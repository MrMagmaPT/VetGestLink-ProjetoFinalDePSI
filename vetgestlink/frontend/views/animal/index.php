<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Animal[] $animaisUsuario */

$this->title = 'Meus Animais';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="animal-index">
        <div class="container py-4" style="min-height: 60vh;">
            <div class="animal-index">
                <div class="text-center mb-4">
                    <h1 class="mb-0">
                        <i class="fas fa-paw text-primary"></i>
                        <?= Html::encode($this->title) ?>
                    </h1>
                </div>
                <div class="row g-4 justify-content-center">
                    <?php if (empty($animaisUsuario)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i>
                            Nenhum animal registado.
                        </div>
                    <?php else: ?>
                        <?php foreach ($animaisUsuario as $animal): ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="card shadow-sm h-100 rounded-4">
                                    <!-- IMAGE -->
                                    <img src="<?= Html::encode($animal->getImageUrl()) ?>" class="card-img-top rounded-top-4" alt="Foto Animal" style="height: 200px; object-fit: cover;">
                                    <div class="card-body text-center d-flex flex-column">
                                        <!-- NAME -->
                                        <h4 class="card-title mb-2"><?= Html::encode($animal->nome) ?></h4>
                                        <!-- SPECIES -->
                                        <p class="text-muted mb-3">
                                            <?= Html::encode($animal->especies->nome ?? "Sem espécie") ?>
                                        </p>
                                        <div class="mt-auto d-flex justify-content-center gap-2">
                                            <?= Html::a('Ver Detalhes', ['view', 'id' => $animal->id], [
                                                'class' => 'btn btn-dark rounded-pill'
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

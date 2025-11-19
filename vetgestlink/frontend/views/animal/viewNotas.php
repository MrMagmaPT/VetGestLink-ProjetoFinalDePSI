<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */
/** @var common\models\Nota[] $allnotas */

$this->title = 'Notas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-3">
    <div class="d-flex justify-content-center align-items-center mb-4">
        <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>
    </div>

    <?php if (!empty($allnotas)) : ?>
        <div class="card mb-3 shadow-sm">
            <ul class="list-group list-group-flush">
                <?php foreach ($allnotas as $nota): ?>
                    <li class="list-group-item">
                        <strong>Data:</strong> <?= Yii::$app->formatter->asDatetime($nota->created_at, 'php:d/m/Y H:i') ?><br>
                        <em>Autor:</em> <?= Html::encode($nota->userprofiles->nomecompleto ?? 'Desconhecido') ?><br>
                        <?= Html::encode($nota->nota) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">Sem notas</p>
    <?php endif; ?>

    <div class="d-flex justify-content-center mt-3">
        <?= Html::a("Nova Nota", ['/animal/create-nota', 'animal_id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Animal $animal */
/** @var common\models\Nota[] $allnotas */

$this->title = 'Notas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-3">
    <div class="d-flex justify-content-center align-items-center mb-4">
        <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="d-flex justify-content-center mt-3">
        <?= Html::a('<i class="fa-solid fa-plus me-1 "></i> Nova Nota', ['/nota/create', 'animalId' => $animal->id], ['class' => 'btn btn-dark rounded-pill']) ?>
    </div>
    <?php if (!empty($allnotas)) : ?>
        <div class="card mb-3 shadow-sm p-3">
            <ul class="list-group list-group-flush">
                <?php foreach ($allnotas as $nota): ?>
                    <li class="list-group-item mb-3 rounded shadow-sm d-flex justify-content-between align-items-start">
                        <div>
                            <strong>Data:</strong> <?= Yii::$app->formatter->asDatetime($nota->created_at, 'php:d/m/Y H:i') ?><br>
                            <em>Autor:</em> <?= Html::encode($nota->userprofiles->nomecompleto ?? 'Desconhecido') ?><br>
                            <?= Html::encode($nota->nota) ?>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <?= Html::a('<i class="fa-solid fa-pen-to-square me-1"></i> Editar', ['/nota/update', 'id' => $nota->id], [
                                'class' => 'btn btn-dark rounded-pill'
                            ]) ?>
                            <?= Html::a('<i class="fas fa-eye me-1"></i> Ver', ['/nota/view', 'id' => $nota->id], [
                                'class' => 'btn btn-dark rounded-pill'
                            ]) ?>
                            <?= Html::a('<i class="fa-solid fa-trash me-1"></i> Apagar', ['/nota/delete', 'id' => $nota->id], [
                                'class' => 'btn btn-dark rounded-pill',
                                'data' => [
                                    'confirm' => 'Tem certeza que deseja apagar esta nota?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">Sem notas</p>
    <?php endif; ?>
</div>



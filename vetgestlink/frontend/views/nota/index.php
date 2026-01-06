<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Animal $animal */
/** @var common\models\Nota[] $allnotas */

$this->title = 'Notas do Animal';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">
                    <i class="fas fa-sticky-note text-primary me-2"></i>
                    <?= Html::encode($this->title) ?>
                </h1>
                <?= Html::a('<i class="fas fa-plus me-2"></i>Nova Nota', ['/nota/create', 'animalId' => $animal->id], ['class' => 'btn btn-primary shadow-sm']) ?>
            </div>
        </div>
    </div>

    <?php if (!empty($allnotas)) : ?>
        <div class="row g-3">
            <?php foreach ($allnotas as $nota): ?>
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-primary">
                                    <i class="far fa-sticky-note me-2"></i>Nota #<?= $nota->id ?>
                                </h6>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?= Yii::$app->formatter->asDatetime($nota->created_at, 'php:d/m/Y H:i') ?>
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="badge bg-success bg-opacity-10 text-white">
                                    <i class="fas fa-user me-1"></i>
                                    <?= Html::encode($nota->userprofiles->nomecompleto ?? 'Desconhecido') ?>
                                </span>
                            </div>
                            <p class="card-text text-muted mb-0"><?= Html::encode($nota->nota) ?></p>
                        </div>
                        <div class="card-footer bg-light border-top">
                            <div class="d-flex justify-content-end gap-2">
                                <?= Html::a('<i class="fas fa-eye me-1"></i>Ver', ['/nota/view', 'id' => $nota->id], [
                                    'class' => 'btn btn-sm btn-outline-primary'
                                ]) ?>
                                <?= Html::a('<i class="fas fa-edit me-1"></i>Editar', ['/nota/update', 'id' => $nota->id], [
                                    'class' => 'btn btn-sm btn-outline-secondary'
                                ]) ?>
                                <?= Html::a('<i class="fas fa-trash-alt me-1"></i>Excluir', ['/nota/delete', 'id' => $nota->id], [
                                    'class' => 'btn btn-sm btn-outline-danger',
                                    'data' => [
                                        'confirm' => 'Tem certeza que deseja excluir esta nota?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center shadow-sm">
            <i class="fas fa-info-circle fa-2x mb-3"></i>
            <p class="mb-0">Nenhuma nota encontrada. Clique em "Nova Nota" para adicionar a primeira nota.</p>
        </div>
    <?php endif; ?>
</div>



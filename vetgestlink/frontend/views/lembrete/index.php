<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Lembrete[] $lembretesUsuario */


$this->title = 'Meus Lembretes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="lembrete-index">
    <div class="container py-4" style="min-height: 60vh;">

        <div class="text-center mb-4">
            <h1 class="mb-0">
                <i class="fa-regular fa-sticky-note text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h1>
        </div>

        <p class="text-center mb-4">
            <?= Html::a('Criar Lembrete', ['create', 'id'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php Pjax::begin(); ?>

        <?php if (empty($lembretesUsuario)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i>
                Nenhum lembrete encontrado.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($lembretesUsuario as $lembrete): ?>
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-header bg-white border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fa-solid fa-map-pin text-primary"></i>
                                        Lembrete #<?= $lembrete->id ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Descrição:</strong>
                                    <p><?= Html::encode($lembrete->descricao) ?></p>
                                </div>
                                <div class="mb-2">
                                    <strong>Criado em:</strong>
                                    <?= Yii::$app->formatter->asDatetime($lembrete->created_at, 'php:d/m/Y H:i') ?>
                                </div>
                                <div class="mb-2">
                                    <strong>Atualizado em:</strong>
                                    <?= Yii::$app->formatter->asDatetime($lembrete->updated_at, 'php:d/m/Y H:i') ?>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-top d-flex justify-content-end">
                                <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $lembrete->id], [
                                    'class' => 'btn btn-outline-primary btn-sm me-2',
                                    'title' => 'Ver'
                                ]) ?>
                                <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id' => $lembrete->id], [
                                    'class' => 'btn btn-outline-secondary btn-sm me-2',
                                    'title' => 'Editar'
                                ]) ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $lembrete->id], [
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'title' => 'Excluir',
                                    'data' => [
                                        'confirm' => 'Tem certeza que deseja excluir este lembrete?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php Pjax::end(); ?>
    </div>
</div>

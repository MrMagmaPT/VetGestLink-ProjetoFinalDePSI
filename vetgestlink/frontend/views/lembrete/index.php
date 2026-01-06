<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Lembrete[] $lembretesUsuario */

$this->title = 'Meus Lembretes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="lembrete-index">
    <div class="container py-4">

        <!-- Cabeçalho -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">
                        <i class="fas fa-bell text-warning me-2"></i>
                        <?= Html::encode($this->title) ?>
                    </h1>
                    <?= Html::a('<i class="fas fa-plus me-2"></i>Novo Lembrete', ['create', 'id'], ['class' => 'btn btn-warning  shadow-sm']) ?>
                </div>
            </div>
        </div>

        <?php Pjax::begin(); ?>

        <?php if (empty($lembretesUsuario)): ?>
            <div class="alert alert-warning text-center shadow-sm border-0">
                <i class="fas fa-bell-slash fa-2x mb-3"></i>
                <p class="mb-0">Nenhum lembrete encontrado. Clique em "Novo Lembrete" para adicionar o primeiro.</p>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($lembretesUsuario as $lembrete): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-thumbtack me-1"></i>#<?= $lembrete->id ?>
                                    </span>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $lembrete->id], [
                                            'class' => 'btn btn-outline-primary btn-sm',
                                            'title' => 'Ver'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $lembrete->id], [
                                            'class' => 'btn btn-outline-secondary btn-sm',
                                            'title' => 'Editar'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id' => $lembrete->id], [
                                            'class' => 'btn btn-outline-danger btn-sm',
                                            'title' => 'Excluir',
                                            'data' => [
                                                'confirm' => 'Tem certeza que deseja excluir este lembrete?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                                
                                <p class="card-text mb-3"><?= Html::encode($lembrete->descricao) ?></p>
                                
                                <div class="border-top pt-3 mt-auto">
                                    <small class="text-muted d-block mb-1">
                                        <i class="far fa-calendar-plus me-1"></i>
                                        <strong>Criado:</strong> <?= Yii::$app->formatter->asDatetime($lembrete->created_at, 'php:d/m/Y H:i') ?>
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="far fa-calendar-check me-1"></i>
                                        <strong>Atualizado:</strong> <?= Yii::$app->formatter->asDatetime($lembrete->updated_at, 'php:d/m/Y H:i') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php Pjax::end(); ?>
    </div>
</div>

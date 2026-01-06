<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\widgets\Alert;

/** @var array $moradas */
?>
<?php if (empty($moradas)): ?>
    <div class="text-center py-4">
        <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
        <p class="text-muted">Nenhuma morada cadastrada.</p>
    </div>
<?php else: ?>
    <?= Alert::widget() ?>
    <?php foreach ($moradas as $i => $morada): ?>
        <?php 
            $moradaId = $morada->id ?? null;
            $isPrincipal = ($morada->principal ?? 0) == 1;
        ?>
        <div class="card mb-3 shadow-sm border-<?= $isPrincipal ? 'success' : 'light' ?>">
            <!-- Header da morada -->
            <div class="card-header bg-<?= $isPrincipal ? 'success' : 'light' ?> text-<?= $isPrincipal ? 'white' : 'dark' ?> d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center">
                    <i class="fas fa-<?= $isPrincipal ? 'star' : 'map-marker-alt' ?> me-2"></i>
                    <strong>
                        <?php if ($isPrincipal): ?>
                            <i class="fas fa-home me-1"></i>Morada Principal
                        <?php else: ?>
                            Morada <?= $i + 1 ?>
                        <?php endif; ?>
                    </strong>
                </div>

                <div class="btn-group btn-group-sm" role="group">
                    <?php if ($moradaId): ?>
                        <?= Html::a('<i class="fas fa-edit"></i>', ['morada/update', 'id' => $moradaId], [
                            'class' => 'btn btn-sm btn-outline-' . ($isPrincipal ? 'light' : 'primary'),
                            'title' => 'Editar morada',
                            'data-toggle' => 'tooltip'
                        ]) ?>
                    <?php endif; ?>

                    <?php if ($moradaId): ?>
                        <?= Html::a('<i class="fas fa-trash-alt"></i>', ['morada/delete', 'id' => $moradaId], [
                            'class' => 'btn btn-sm btn-' . ($isPrincipal ? 'outline-light' : 'outline-danger'),
                            'title' => 'Remover morada',
                            'data' => [
                                'method' => 'post',
                                'pjax' => 1,
                                'confirm' => 'Tem certeza que deseja remover esta morada?'
                            ],
                            'data-toggle' => 'tooltip'
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Body com detalhes da morada -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-9">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-road text-success me-2 mt-1"></i>
                            <div>
                                <small class="text-muted d-block">Endereço</small>
                                <strong><?= Html::encode($morada->rua ?? '-') ?>, Nº <?= Html::encode($morada->nporta ?? '-') ?></strong>
                                <?php if (!empty($morada->andar)): ?>
                                    <span class="text-muted">(<?= Html::encode($morada->andar) ?>)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-mail-bulk text-success me-2 mt-1"></i>
                            <div>
                                <small class="text-muted d-block">Código Postal</small>
                                <strong><?= Html::encode($morada->cdpostal ?? '-') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-city text-success me-2 mt-1"></i>
                            <div>
                                <small class="text-muted d-block">Cidade</small>
                                <strong><?= Html::encode($morada->cidade ?? '-') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marked-alt text-success me-2 mt-1"></i>
                            <div>
                                <small class="text-muted d-block">Localidade</small>
                                <strong><?= Html::encode($morada->localidade ?? '-') ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($morada->cxpostal)): ?>
                        <div class="col-md-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-inbox text-success me-2 mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Caixa Postal</small>
                                    <strong><?= Html::encode($morada->cxpostal) ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

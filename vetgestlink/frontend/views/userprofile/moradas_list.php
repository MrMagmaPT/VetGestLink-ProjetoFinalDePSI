<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\widgets\Alert;

/** @var array $moradas */

$moradaId = $morada->id ?? null;
?>
<?php if (empty($moradas)): ?>
    <p class="text-muted">Nenhuma morada cadastrada.</p>
<?php else: ?>
    <?= Alert::widget() ?>
    <?php foreach ($moradas as $i => $morada): ?>
        <?php $moradaId = $morada->id ?? null; ?>
        <div class="morada-item mb-3 border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <strong>Morada <?= $i === 0 ? 'Principal' : ($i + 1) ?></strong>

                <div>
                    <?php if ($moradaId): ?>
                        <?= Html::a('Editar', ['morada/update', 'id' => $moradaId], ['class' => 'btn btn-sm btn-outline-primary me-2']) ?>
                    <?php endif; ?>

                    <?php if ($moradaId && $i !== 0): // só permitir remover moradas secundárias que tenham id ?>
                        <?= Html::a('Remover', ['morada/delete', 'id' => $moradaId], [
                            'class' => 'btn btn-sm btn-danger',
                            'data' => [ 'method' => 'post', 'pjax' => 1 ],
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Detalhes da morada -->
            <div class="row g-2 mt-2">
                <div class="col-md-6">
                    <strong>Rua:</strong> <?= Html::encode($morada->rua ?? '') ?>
                </div>
                <div class="col-md-3">
                    <strong>Nº Porta:</strong> <?= Html::encode($morada->nporta ?? '') ?>
                </div>
                <div class="col-md-3">
                    <strong>Andar:</strong> <?= Html::encode($morada->andar ?? '') ?>
                </div>
                <div class="col-md-4 mt-2">
                    <strong>Código Postal:</strong> <?= Html::encode($morada->cdpostal ?? '') ?>
                </div>
                <div class="col-md-4 mt-2">
                    <strong>Cidade:</strong> <?= Html::encode($morada->cidade ?? '') ?>
                </div>
                <div class="col-md-4 mt-2">
                    <strong>Cx Postal:</strong> <?= Html::encode($morada->cxpostal ?? '') ?>
                </div>
                <div class="col-md-4 mt-2">
                    <strong>Localidade:</strong> <?= Html::encode($morada->localidade ?? '') ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

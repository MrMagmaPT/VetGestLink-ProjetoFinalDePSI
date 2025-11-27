<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var array $moradas */
?>
<?php foreach ($moradas as $i => $morada): ?>
    <div class="morada-item mb-3 border-bottom pb-3">
        <div class="d-flex justify-content-between align-items-center">
            <strong>Morada <?= $i === 0 ? 'Principal' : ($i + 1) ?></strong>
            <?= Html::hiddenInput("Morada[{$i}][id]", ArrayHelper::getValue($morada, 'id', '')) ?>
            <?= Html::hiddenInput("Morada[{$i}][eliminado]", ArrayHelper::getValue($morada, 'eliminado', 0)) ?>

            <div>
                <?php $mid = ArrayHelper::getValue($morada, 'id', null); ?>
                <?php if ($mid): ?>
                    <?= Html::a('Editar', ['userprofile/add-morada', 'id' => $mid], ['class' => 'btn btn-sm btn-outline-primary me-2']) ?>
                <?php endif; ?>

                <?php if ($mid && $i !== 0): // só permitir remover moradas secundárias que tenham id ?>
                    <?= Html::a('Remover', ['userprofile/remove-morada', 'id' => $mid], [
                        'class' => 'btn btn-sm btn-danger',
                        'data' => [ 'method' => 'post', 'pjax' => 1 ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-2 mt-2">
            <div class="col-md-6"><?= Html::textInput("Morada[{$i}][rua]", ArrayHelper::getValue($morada, 'rua', ''), ['class' => 'form-control', 'placeholder' => 'Rua', 'readonly' => true]) ?></div>
            <div class="col-md-3"><?= Html::textInput("Morada[{$i}][nporta]", ArrayHelper::getValue($morada, 'nporta', ''), ['class' => 'form-control', 'placeholder' => 'Nº Porta', 'readonly' => true]) ?></div>
            <div class="col-md-3"><?= Html::textInput("Morada[{$i}][andar]", ArrayHelper::getValue($morada, 'andar', ''), ['class' => 'form-control', 'placeholder' => 'Andar', 'readonly' => true]) ?></div>
            <div class="col-md-4 mt-2"><?= Html::textInput("Morada[{$i}][cdpostal]", ArrayHelper::getValue($morada, 'cdpostal', ''), ['class' => 'form-control', 'placeholder' => 'Código Postal', 'readonly' => true]) ?></div>
            <div class="col-md-4 mt-2"><?= Html::textInput("Morada[{$i}][cidade]", ArrayHelper::getValue($morada, 'cidade', ''), ['class' => 'form-control', 'placeholder' => 'Cidade', 'readonly' => true]) ?></div>
            <div class="col-md-4 mt-2"><?= Html::textInput("Morada[{$i}][cxpostal]", ArrayHelper::getValue($morada, 'cxpostal', ''), ['class' => 'form-control', 'placeholder' => 'Cx Postal', 'readonly' => true]) ?></div>
            <div class="col-md-4 mt-2"><?= Html::textInput("Morada[{$i}][localidade]", ArrayHelper::getValue($morada, 'localidade', ''), ['class' => 'form-control', 'placeholder' => 'Localidade', 'readonly' => true]) ?></div>
        </div>
    </div>
<?php endforeach; ?>


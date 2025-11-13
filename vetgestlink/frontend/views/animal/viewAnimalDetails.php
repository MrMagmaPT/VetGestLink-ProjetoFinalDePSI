<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var common\models\Animal $model */
?>

<div class="container p-4">

    <h2 class="fw-bold text-center mb-3"><?= Html::encode($model->nome) ?></h2>

    <div class="text-center mb-4">
        <img src="<?= $model->foto ?? '/img/default-animal.jpg' ?>"
             style="max-width:300px; border-radius:20px;">
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-borderless'],
        'attributes' => [
            'nome',
            [
                'attribute' => 'dtanascimento',
                'format' => ['date', 'php:d/m/Y'],
                'label' => 'Data de Nascimento'
            ],
            'peso',
            [
                'attribute' => 'sexo',
                'value' => $model->sexo == 'M' ? 'Macho' : 'Fêmea'
            ],
            [
                'attribute' => 'especies_id',
                'label' => 'Espécie',
                'value' => $model->especies->nome ?? 'Não definido'
            ],
            [
                'attribute' => 'racas_id',
                'label' => 'Raça',
                'value' => $model->racas->nome ?? 'Não definido'
            ],
        ]
    ]) ?>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <h4>Última Nota</h4>
        <?= Html::a('Ver Notas', ['/animal/view-notas', 'animal_id' => $model->id], ['class' => 'text-decoration-none text-primary']) ?>
    </div>

    <?php if ($latestNota): ?>
        <div class="list-group-item">
            <strong><?= Yii::$app->formatter->asDate($latestNota->created_at) ?></strong><br>
            <em><?= Html::encode($latestNota->userprofiles->nomecompleto ?? 'Desconhecido') ?></em><br>
            <?= Html::encode($latestNota->nota) ?>
        </div>
    <?php else: ?>
        <p class="text-muted">Sem notas</p>
    <?php endif; ?>


    <div class="mt-2 d-flex justify-content-center justify-content-between">
        <button class="btn" data-dismiss="modal">Fechar</button>
        <?= Html::a("Nova Nota", ['/animal/create-nota', 'animal_id' => $model->id], ['class' => 'btn']) ?>
    </div>
</div>


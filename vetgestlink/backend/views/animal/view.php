<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Animal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="animais-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="animal-image mb-3">
        <?= Html::img($model->getImageUrl(), ['alt' => $model->nome, 'class' => 'img-thumbnail', 'style' => 'max-width: 300px']) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            [
                'attribute' => 'dtanascimento',
                'label' => 'Data de Nascimento',
            ],
            'peso',
            [
                'attribute' => 'microship',
                'value' => $model->microship ? 'Sim' : 'Não',
                'label' => 'Microchip',
            ],
            [
                'attribute' => 'sexo',
                'value' => $model->sexo == 'M' ? 'Macho' : 'Fêmea',
            ],
            [
                'attribute' => 'especies_id',
                'value' => $model->especies->nome ?? '-',
                'label' => 'Espécie',
            ],
            [
                'attribute' => 'racas_id',
                'value' => $model->racas->nome ?? '-',
                'label' => 'Raça',
            ],
            [
                'attribute' => 'userprofiles_id',
                'value' => $model->userprofiles->nomecompleto ?? '-',
                'label' => 'Proprietário',
            ],
        ],
    ]) ?>

    <h3 class="mt-4">Notas</h3>
    <?php if (!empty($model->notas)): ?>
        <div class="list-group">
            <?php foreach ($model->notas as $nota): ?>
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Nota #<?= $nota->id ?></h5>
                        <small><?= Yii::$app->formatter->asDatetime($nota->created_at) ?></small>
                    </div>
                    <p class="mb-1"><?= Html::encode($nota->nota) ?></p>
                    <small>Por: <?= Html::encode($nota->userprofiles->nomecompleto ?? 'N/A') ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">Sem notas registadas para este animal.</p>
    <?php endif; ?>

</div>

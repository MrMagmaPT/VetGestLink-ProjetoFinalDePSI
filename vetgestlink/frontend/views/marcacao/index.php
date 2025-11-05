<?php

use common\models\Marcacao;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Marcações';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="marcacoes-index container py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>
    </div>

    <?php foreach ($dataProvider->models as $model): ?>
        <div class="card mb-3 shadow-sm">

            <div class="card-body d-flex justify-content-between">

                <div>
                    <h5 class="card-title mb-2">
                        Marcação #<?= Html::encode($model->id) ?>
                    </h5>

                    <p class="mb-1">
                        <strong>📅 Data:</strong>
                        <?= Html::encode($model->data) ?>
                    </p>

                    <p class="mb-1">
                        <strong>🕒 Hora:</strong>
                        <?= Html::encode($model->horainicio) ?> —
                        <?= Html::encode($model->horafim) ?>
                    </p>

                    <p class="text-muted mb-0">
                        Criado em:
                        <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

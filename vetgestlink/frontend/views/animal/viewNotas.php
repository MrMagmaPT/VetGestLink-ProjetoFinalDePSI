<?php use yii\helpers\Html;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Notas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-3">
    <div class="d-flex justify-content-center align-items-center mb-4">
        <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>
    </div>
    <?php if (!empty($model->notas)) : ?>
    <div class="card mb-3 shadow-sm">
        <ul>
            <?php foreach ($model->notas as $nota): ?>
                <li class="list-group-item">
                    <strong>Data: <?= Yii::$app->formatter->asDate($nota->create_at) ?></strong>
                    <br>
                    <em>Autor:<?= Html::encode($nota->userprofiles->nomecompleto ?? 'Desconhecido') ?></em>
                    <br>
                    <?= Html::encode($nota->nota) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php else: ?>
    <p class="text-muted">Sem notas</p>
<?php endif; ?>

<?= Html::a("Nova Nota", ['/animal/create-nota', 'animal_id' => $model->id], ['class' => 'btn d-flex justify-content-center align-items-center mb-4']) ?>

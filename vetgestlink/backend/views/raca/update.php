<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Raca $model */

$this->title = 'Update Raca: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Raca', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="racas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

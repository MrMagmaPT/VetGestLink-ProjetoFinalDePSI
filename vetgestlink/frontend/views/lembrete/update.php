<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Lembrete $model */

$this->title = 'Update Lembrete: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lembretes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lembrete-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

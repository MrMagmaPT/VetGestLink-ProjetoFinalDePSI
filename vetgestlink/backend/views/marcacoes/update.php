<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Marcacoes $model */

$this->title = 'Update Marcacoes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Marcacoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="marcacoes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */

$this->title = 'Create Marcacao';
$this->params['breadcrumbs'][] = ['label' => 'Marcacao', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marcacoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

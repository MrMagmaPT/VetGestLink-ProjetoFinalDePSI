<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Raca $model */

$this->title = 'Create Raca';
$this->params['breadcrumbs'][] = ['label' => 'Raca', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="racas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Especie $model */

$this->title = 'Create Especie';
$this->params['breadcrumbs'][] = ['label' => 'Especie', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="especies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

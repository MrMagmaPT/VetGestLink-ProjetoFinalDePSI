<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */

$this->title = 'Create Nota';
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nota-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Animais $model */

$this->title = 'Create Animais';
$this->params['breadcrumbs'][] = ['label' => 'Animais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animais-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

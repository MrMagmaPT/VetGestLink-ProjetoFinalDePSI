<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */

$this->title = 'Create Nota';
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-3">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

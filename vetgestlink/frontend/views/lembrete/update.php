<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Lembrete $lembrete */

$this->title = 'Update Lembrete: ' . $lembrete->id;
$this->params['breadcrumbs'][] = ['label' => 'Lembretes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $lembrete->id, 'url' => ['view', 'id' => $lembrete->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="container py-3">

    <?= $this->render('_form', [
        'lembrete' => $lembrete,
    ]) ?>
</div>

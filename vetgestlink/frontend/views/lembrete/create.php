<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Lembrete $lembrete */

$this->title = 'Create Lembrete';
$this->params['breadcrumbs'][] = ['label' => 'Lembretes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-3">

    <?= $this->render('_form', [
        'lembrete' => $lembrete,
    ]) ?>
</div>

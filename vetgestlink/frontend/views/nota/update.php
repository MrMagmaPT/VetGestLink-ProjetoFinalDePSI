<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Nota $model */

$this->title = 'Editar Nota';
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Editar';
?>

<div class="container py-3">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

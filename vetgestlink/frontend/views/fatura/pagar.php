<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */

$this->title = 'Pagar Fatura: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fatura', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Pagar';
?>
<div class="faturas-update">


    <?= $this->render('_pagar', [
        'model' => $model,
        'metodos' => $metodos,
    ]) ?>

</div>

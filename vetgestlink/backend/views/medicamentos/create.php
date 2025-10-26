<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Medicamentos $model */

$this->title = 'Create Medicamentos';
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicamentos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

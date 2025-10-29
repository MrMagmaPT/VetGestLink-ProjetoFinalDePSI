<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Marcacoes $model */

$this->title = 'Create Marcacoes';
$this->params['breadcrumbs'][] = ['label' => 'Marcacoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marcacoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

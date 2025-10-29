<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Especies $model */

$this->title = 'Create Especies';
$this->params['breadcrumbs'][] = ['label' => 'Especies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="especies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

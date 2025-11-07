<?php

/** @var yii\web\View $this */
/** @var common\models\Nota $model */

use yii\helpers\Html;

$this->title = 'Criar Nota';
$this->params['breadcrumbs'][] = ['label' => 'Animal', 'url' => ['animal/view', 'id' => $model->animais_id]];
$this->params['breadcrumbs'][] = $this->title;

// Register CSS
$this->registerCssFile('@web/static/css/custom-variables.css', ['depends' => [\yii\web\YiiAsset::class]]);
?>

<div class="nota-create container py-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


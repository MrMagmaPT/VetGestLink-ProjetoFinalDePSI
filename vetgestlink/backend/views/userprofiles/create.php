<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Userprofiles $model */

$this->title = 'Create Userprofiles';
$this->params['breadcrumbs'][] = ['label' => 'Userprofiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userprofiles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

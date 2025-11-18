<?php

use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var backend\models\SignupFormBackend $model */

$this->title = 'Update Userprofile: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Userprofile', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="userprofiles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    
    ]) ?>

</div>

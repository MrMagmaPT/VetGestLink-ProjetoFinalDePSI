<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */

$this->title = 'Create Userprofile';
$this->params['breadcrumbs'][] = ['label' => 'Userprofile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userprofiles-create">

    <?= $this->render('createuserform', [
        'model' => $model,
    ]) ?>

</div>

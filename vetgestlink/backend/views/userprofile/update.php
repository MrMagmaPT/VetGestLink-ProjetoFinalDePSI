<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\SignupFormBackend $model */
/** @var common\models\Userprofile $userprofile */

$this->title = 'Atualizar Perfil: ' . $userprofile->nomecompleto;
$this->params['breadcrumbs'][] = ['label' => 'Userprofiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $userprofile->id, 'url' => ['view', 'id' => $userprofile->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<div class="userprofile-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
            'userprofile' => $userprofile,
    ]) ?>
</div>

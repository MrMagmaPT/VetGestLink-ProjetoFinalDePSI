<?php

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\Morada[] $moradas */

use yii\helpers\Html;

$this->title = 'Atualizar Perfil';
$this->params['breadcrumbs'][] = ['label' => 'Perfil', 'url' => ['userprofile/view']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<div class="userprofile-update container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow border-0 rounded-4">


                
            <?= $this->render('_form', [
                'model' => $model,
                'moradas' => $moradas,
            ]) ?>
                

            </div>
        </div>
    </div>
</div>

<?php

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\Morada[] $moradas */

use yii\helpers\Html;

$this->title = 'Atualizar Perfil';
$this->params['breadcrumbs'][] = ['label' => 'Perfil', 'url' => ['userprofile/view']];
$this->params['breadcrumbs'][] = 'Atualizar';

// Register CSS
$this->registerCssFile('@web/static/css/custom-variables.css', ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerCssFile('@web/static/css/userprofile.css', ['depends' => [\yii\web\YiiAsset::class]]);
?>

<div class="userprofile-update container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow border-0 rounded-4">
                <div class="card-header text-center text-white py-4">
                    <h4 class="mb-0 fw-bold"><?= Html::encode($this->title) ?></h4>
                </div>

                <div class="card-body p-4">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'moradas' => $moradas,
                    ]) ?>
                </div>

            </div>
        </div>
    </div>
</div>


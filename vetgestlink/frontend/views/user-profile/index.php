<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;

$user = $dataProvider->getModels();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-dark text-white text-center">
                    <?= var_dump($user);die; ?>
                    <h3 class="mt-3 mb-0"><?= Html::encode($user->nomecompleto) ?></h3>
                    <p class="text-muted mb-2"><?= Html::encode($user->email) ?></p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5 class="strong">Email</h5>
                            <p><strong>Email:</strong> <?= Html::encode($user->email) ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="strong">Morada</h5>
                            <p><strong>Morada 1:</strong> <?= Html::encode($userProfile->morada) ?></p>
                            <p><strong>Morada 2:</strong><?= Html::encode($userProfile->morada2 ?: '<sem dados>') ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="strong">Informação adicional</h5>
                            <p><strong>NIF:</strong> <?= Html::encode($userProfile->nif) ?></p>
                            <p><strong>Código Postal:</strong> <?= Html::encode($userProfile->codigoPostal) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="strong">Outros</h5>
                            <p><strong>Data de criação:</strong> <?= Html::encode(Yii::$app->formatter->asDate($user->created_at, 'long')) ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end bg-light">
                    <?= Html::a('Editar Perfil', ['profile/edit'], ['class' => 'site-btn']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\Morada[] $moradas */

$this->title = 'Atualizar Perfil: ' . $model->nomecompleto;
$this->params['breadcrumbs'][] = ['label' => 'Perfis de Utilizador', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nomecompleto, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-user-edit text-primary"></i>
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Perfis', ['index']) ?></li>
                    <li class="breadcrumb-item active">Atualizar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <?= $this->render('_form', [
            'model' => $model,
            'moradas' => $moradas,
        ]) ?>
    </div>
</div>

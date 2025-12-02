<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */

$this->title = 'Nova Marcação';
$this->params['breadcrumbs'][] = ['label' => 'Marcações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-calendar-plus text-success"></i>
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Marcações', ['index']) ?></li>
                    <li class="breadcrumb-item active">Nova</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

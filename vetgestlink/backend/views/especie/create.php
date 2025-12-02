<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Especie $model */

$this->title = 'Nova Espécie';
$this->params['breadcrumbs'][] = ['label' => 'Espécies', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Nova';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-paw text-primary"></i>
                    Nova Espécie
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Home', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Espécies', ['index']) ?></li>
                    <li class="breadcrumb-item active">Nova</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Nova Espécie</h5>
            </div>
            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>

            </div>
            
        </div>
    </div>
</div>

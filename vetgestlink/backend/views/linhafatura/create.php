<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Linhafatura $model */
/** @var array $medicamentosList */
/** @var array $servicosList */
/** @var array $marcacoesList */

$this->title = 'Adicionar Linha à Fatura #' . $model->faturas_id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['/fatura/index']];
$this->params['breadcrumbs'][] = ['label' => 'Fatura #' . $model->faturas_id, 'url' => ['/fatura/view', 'id' => $model->faturas_id]];
$this->params['breadcrumbs'][] = 'Adicionar Linha';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-plus-circle text-success"></i>
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Faturas', ['/fatura/index']) ?></li>
                    <li class="breadcrumb-item"><?= Html::a('Fatura #' . $model->faturas_id, ['/fatura/view', 'id' => $model->faturas_id]) ?></li>
                    <li class="breadcrumb-item active">Adicionar Linha</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <?= $this->render('_form', [
            'model' => $model,
            'medicamentosList' => $medicamentosList,
            'servicosList' => $servicosList,
            'marcacoesList' => $marcacoesList,
        ]) ?>
    </div>
</div>

<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */

$this->title = 'Editar Medicamento: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Editar Medicamento: ' . $model->nome,
    'icon' => 'fa-edit text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Medicamentos',
            'url' => ['index'],
        ],
        [
            'label' => $model->nome,
            'url' => ['view', 'id' => $model->id],
        ],
        [
            'label' => 'Editar',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <?= $this->render('_form', [
            'model' => $model,
            'searchModel' => $searchModel,
        ]) ?>
    </div>
</div>

<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */

$this->title = 'Novo Medicamento';
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Novo Medicamento',
    'icon' => 'fa-pills text-success',
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
            'label' => 'Novo',
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

<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Raca $model */

$this->title = 'Nova Raça';
$this->params['breadcrumbs'][] = ['label' => 'Raças', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Nova Raça',
    'icon' => 'fa-dog text-success',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Raças',
            'url' => ['index'],
        ],
        [
            'label' => 'Nova Raça',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <?= $this->render('_form', [
            'model' => $model,
            'especiesAtivas' => $especiesAtivas,
        ]) ?>
    </div>
</div>

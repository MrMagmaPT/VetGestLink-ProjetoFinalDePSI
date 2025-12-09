<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Raca $model */

$this->title = 'Atualizar Raça: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Raca', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Atualizar Raça',
    'icon' => 'fa-edit text-primary',
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
            'label' => 'Atualizar',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

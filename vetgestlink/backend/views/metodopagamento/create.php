<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Metodopagamento $model */

$this->title = 'Novo Método de Pagamento';
$this->params['breadcrumbs'][] = ['label' => 'Métodos de Pagamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Novo Método de Pagamento',
    'icon' => 'fa-credit-card text-success',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Métodos de Pagamento',
            'url' => ['index'],
        ],
        [
            'label' => 'Novo Método',
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

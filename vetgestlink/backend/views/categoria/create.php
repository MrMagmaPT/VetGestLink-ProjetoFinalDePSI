<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Categoria $model */

$this->title = 'Nova Categoria';
$this->params['breadcrumbs'][] = ['label' => 'Categorias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Nova Categoria',
    'icon' => 'fa-folder-plus text-success',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Categorias',
            'url' => ['index'],
        ],
        [
            'label' => 'Nova Categoria',
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

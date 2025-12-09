<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Categoria $model */

$this->title = 'Editar Categoria: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Categorias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<?php
echo PageHeaderWidget::widget([
    'title' => 'Editar Categoria: ' . $model->nome,
    'icon' => 'fa-edit text-primary',
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
            'label' => $model->nome,
            'url' => ['view', 'id' => $model->id],
        ],
        [
            'label' => 'Editar Categoria',
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

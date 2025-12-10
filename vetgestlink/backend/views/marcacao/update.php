<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */
/** @var array $animaisList */
/** @var array $veterinariosArray */
/** @var array $medicamentos */

$this->title = 'Atualizar Marcação: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Marcações', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Atualizar Marcação',
    'icon' => 'fa-edit text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Marcações',
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
            'animaisList' => $animaisList,
            'veterinariosArray' => $veterinariosArray,
            'medicamentos' => $medicamentos,
        ]) ?>
    </div>
</div>

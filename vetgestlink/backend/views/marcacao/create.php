<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Marcacao $model */
/** @var array $animaisList */
/** @var array $veterinariosArray */
/** @var array $medicamentos */

$this->title = 'Nova Marcação';
$this->params['breadcrumbs'][] = ['label' => 'Marcações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Nova Marcação',
    'icon' => 'fa-calendar-plus text-success',
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
            'label' => 'Nova',
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

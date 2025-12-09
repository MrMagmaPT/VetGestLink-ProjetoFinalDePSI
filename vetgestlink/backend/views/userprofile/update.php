<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\Morada[] $moradas */

$this->title = 'Atualizar Perfil: ' . $model->nomecompleto;
$this->params['breadcrumbs'][] = ['label' => 'Perfis de Utilizador', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nomecompleto, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Atualizar Perfil: ' . $model->nomecompleto,
    'icon' => 'fa-user-edit text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Perfis',
            'url' => ['index'],
        ],
        [
            'label' => $model->nomecompleto,
            'url' => ['view', 'id' => $model->id],
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
            'moradas' => $moradas,
        ]) ?>
    </div>
</div>

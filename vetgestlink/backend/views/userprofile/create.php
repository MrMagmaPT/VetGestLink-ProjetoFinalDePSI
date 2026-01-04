<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */

$this->title = 'Novo Perfil de Utilizador';
$this->params['breadcrumbs'][] = ['label' => 'Perfis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Novo Perfil de Utilizador',
    'icon' => 'fa-user-plus text-success',
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
            'label' => 'Novo Perfil',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <?= $this->render('_createuserform', [
            'model' => $model,
        ]) ?>
    </div>
</div>

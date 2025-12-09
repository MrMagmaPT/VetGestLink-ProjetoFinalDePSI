<?php
use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Servico $model */

$this->title = 'Editar Serviço';
$this->params['breadcrumbs'][] = ['label' => 'Serviços', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Editar';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Editar Serviço',
    'icon' => 'fa-edit text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Serviços',
            'url' => ['index'],
        ],
        [
            'label' => 'Editar',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Serviço</h5>
            </div>
            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>

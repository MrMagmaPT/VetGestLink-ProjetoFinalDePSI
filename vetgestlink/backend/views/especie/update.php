<?php
use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Especie $model */

$this->title = 'Editar Espécie';
$this->params['breadcrumbs'][] = ['label' => 'Espécies', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Editar';
?>

<?php
echo PageHeaderWidget::widget([
    'title' => 'Editar Espécie',
    'icon' => 'fa-edit text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Espécies',
            'url' => ['index'],
        ],
        [
            'label' => 'Editar Espécie',
        ],
    ],
]);
?>

<div class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Espécie</h5>
            </div>
            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    </div>
</div>

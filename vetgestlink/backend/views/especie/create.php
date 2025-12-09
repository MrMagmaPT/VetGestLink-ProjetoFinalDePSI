<?php
use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Especie $model */

$this->title = 'Nova Espécie';
$this->params['breadcrumbs'][] = ['label' => 'Espécies', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Nova';
?>
<?php
echo PageHeaderWidget::widget([
    'title' => 'Nova Espécie',
    'icon' => 'fa-dog text-success',
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
            'label' => 'Nova Espécie',
        ],
    ],
]); 
?>
<div class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Nova Espécie</h5>
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

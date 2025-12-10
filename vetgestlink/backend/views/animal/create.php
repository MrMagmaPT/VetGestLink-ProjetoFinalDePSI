<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */
/** @var array $especiesList */
/** @var array $racasList */
/** @var array $userprofilesList */

?>
<?php
echo PageHeaderWidget::widget([
    'title' => 'Novo Animal',
    'icon' => 'fa-dog text-primary',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Animais',
            'url' => ['index'],
        ],
        [
            'label' => 'Novo',
        ],
    ],
]);
?>
<div class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Novo Animal</h5>
            </div>
            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'especiesList' => $especiesList,
                    'racasList' => $racasList,
                    'userprofilesList' => $userprofilesList,
                ]) ?>
            </div>
        </div>
    </div>
</div>

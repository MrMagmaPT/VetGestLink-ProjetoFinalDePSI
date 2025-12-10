<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */
/** @var array $metodosPagamento */
/** @var array $userprofilesList */

echo PageHeaderWidget::widget([
    'title' => 'Nova Fatura',
    'icon' => 'fa-file-invoice-dollar text-success',
    'breadcrumbs' => [
        [
            'label' => '<i class="fas fa-home"></i> Dashboard',
            'url' => ['/site/index'],
        ],
        [
            'label' => 'Faturas',
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
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Nova Fatura</h5>
            </div>
            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'metodosPagamento' => $metodosPagamento,
                    'userprofilesList' => $userprofilesList,
                ]) ?>
            </div>
        </div>
    </div>
</div>

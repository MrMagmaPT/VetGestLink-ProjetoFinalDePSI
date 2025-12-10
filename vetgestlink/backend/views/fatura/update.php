<?php

use yii\helpers\Html;
use backend\widgets\PageHeaderWidget;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */
/** @var array $metodosPagamento */
/** @var array $userprofilesList */

echo PageHeaderWidget::widget([
    'title' => 'Editar Fatura #' . $model->id,
    'icon' => 'fa-edit text-primary',
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
            'label' => 'Fatura #' . $model->id,
            'url' => ['view', 'id' => $model->id],
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
                <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Fatura #<?= $model->id ?></h5>
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

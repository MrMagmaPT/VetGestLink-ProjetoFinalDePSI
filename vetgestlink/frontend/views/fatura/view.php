<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */

$this->title = "Fatura # {$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

// Register CSS
$this->registerCssFile('@web/static/css/custom-variables.css', ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerCssFile('@web/static/css/fatura.css', ['depends' => [\yii\web\YiiAsset::class]]);
?>

<div class="faturas-view container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow fatura-card border-0">

                <!-- Header -->
                <div class="card-header text-white text-center py-4 fatura-header">
                    <h2 class="fw-bold mb-0"><?= Html::encode($this->title) ?></h2>
                    <small>Informações detalhadas da fatura</small>
                </div>

                <!-- Body -->
                <div class="card-body px-4 py-4">

                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-borderless mb-0'],
                        'attributes' => [
                            [
                                'label' => 'Cliente',
                                'value' => $model->userprofiles->nomecompleto ?? "N/A",
                            ],
                            [
                                'label' => 'Total (€)',
                                'value' => number_format($model->total, 2) . ' €',
                            ],
                            [
                                'attribute' => 'data',
                                'format' => ['date', 'php:d/m/Y'],
                                'label' => 'Data da Fatura',
                            ],
                            [
                                'attribute' => 'estado',
                                'label' => 'Estado',
                                'value' => function ($model) {
                                    return match ($model->estado) {
                                        0 => 'Em espera',
                                        1 => 'Paga',
                                        default => 'Indefinido'
                                    };
                                }
                            ],
                        ],
                    ]) ?>

                    <?php if ($model->estado == 1): ?>
                        <div class="alert alert-success mt-3">
                            <strong>Método de Pagamento:</strong>
                            <?= $model->metodospagamentos->nome ?? "N/A" ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light d-flex justify-content-between py-3 rounded-bottom-4">

                    <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>

                    <div>
                        <?php if ($model->estado != 1): ?>
                            <?= Html::a('Pagar', ['pay', 'id' => $model->id], [
                                'class' => 'btn btn-accent',
                            ]) ?>
                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>


<!-- LINHAS FATURA SECTION -->
<div class="container mt-4">
    <h4 class="mb-3">Linhas da Fatura</h4>

    <table class="table table-striped table-hover shadow-sm rounded">
        <thead class="table-dark">
        <tr>
            <th>Item</th>
            <th>Quantidade</th>
            <th>Total (€)</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($model->linhasfaturas as $linha): ?>
            <tr>
                <td>
                    <?php
                    if ($linha->medicamentos_id) {
                        echo $linha->medicamentos->nome;
                    } elseif ($linha->marcacoes_id) {
                        echo "Consulta - " . ($linha->marcacoes->descricao ?? "");
                    } else {
                        echo "N/A";
                    }
                    ?>
                </td>

                <td><?= $linha->quantidade ?></td>
                <td><?= number_format($linha->total, 2) ?> €</td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>


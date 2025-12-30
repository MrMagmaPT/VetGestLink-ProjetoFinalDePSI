<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\PaymentMethodWidget;

/** @var common\models\Fatura $model */
/** @var common\models\Metodopagamento[] $metodos */

$this->title = "Pagamento da Fatura #" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Fatura #' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Pagamento';

?>

<div class="fatura-pagar">
    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-credit-card text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h2>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <div class="row">
            <!-- Coluna Esquerda: Métodos de Pagamento -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-money-check-alt"></i>
                            Selecione o Método de Pagamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['id' => 'payment-form']); ?>

                        <?= PaymentMethodWidget::widget([
                            'form' => $form,
                            'model' => $model,
                            'attribute' => 'metodospagamentos_id',
                            'metodos' => $metodos,
                        ]) ?>

                        <div class="d-grid gap-2 mt-4">
                            <?= Html::submitButton(
                                '<i class="fas fa-check-circle"></i> Confirmar Pagamento',
                                ['class' => 'btn btn-success btn-lg']
                            ) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Resumo da Fatura -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Resumo da Fatura
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-hashtag"></i> Número da Fatura
                            </small>
                            <strong>#<?= $model->id ?></strong>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">
                                <i class="far fa-calendar"></i> Data de Emissão
                            </small>
                            <strong><?= Yii::$app->formatter->asDate($model->created_at, 'php:d/m/Y') ?></strong>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-user"></i> Cliente
                            </small>
                            <strong><?= Html::encode($model->userprofiles->nomecompleto ?? 'N/A') ?></strong>
                        </div>

                        <div class="text-center pt-3">
                            <small class="text-muted d-block mb-2">Total a Pagar</small>
                            <h2 class="text-success mb-0">
                                <?= Yii::$app->formatter->asCurrency($model->total, 'EUR') ?>
                            </h2>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center">
                        <small class="text-muted">
                            <i class="fas fa-lock"></i> Pagamento seguro
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
?>

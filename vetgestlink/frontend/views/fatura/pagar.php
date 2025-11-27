<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model app\models\Fatura */
/** @var $metodos app\models\MetodosPagamentos[] */

$this->title = "Pagamento";
?>

<div class="container py-4">

    <!-- HEADER -->
    <div class="text-center mb-4">
        <h2 class="fw-bold"><?= Html::encode($this->title) ?></h2>
        <p class="text-muted">Selecione a forma de pagamento pretendida</p>
    </div>

    <div class="row justify-content-center">

        <!-- LEFT SIDE: MÉTODOS DE PAGAMENTO -->
        <div class="col-md-7">
            <div class="card shadow-sm p-4">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'metodospagamentos_id')->radioList(
                    \yii\helpers\ArrayHelper::map($metodos, 'id', 'nome'),
                    [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "
                            <label class='list-group-item d-flex align-items-center' style='cursor:pointer;'>
                                <input type='radio'
                                       name='$name'
                                       value='$value'
                                       class='form-check-input me-3'
                                       " . ($checked ? "checked" : "") . ">
                                <div>
                                    <div class='fw-semibold'>" . Html::encode($label) . "</div>
                                </div>
                            </label>
                            <br>";
                        },
                        'encode' => false, // allow HTML inside item
                    ]
                )->label('Escolha o Método de Pagamento:') ?>


                <div class="text-end mt-4">
                    <?= Html::submitButton("Confirmar Pagamento", [
                        'class' => 'btn btn-dark rounded-pill'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

        <!-- RIGHT SIDE: RESUMO -->
        <div class="col-md-4 mt-4 mt-md-0">
            <div class="card shadow-sm p-4">
                <h5 class="fw-bold">Resumo do Pagamento</h5>

                <p class="mt-3 mb-1 text-muted">Data da Fatura</p>
                <p class="fw-semibold"><?= Yii::$app->formatter->asDate($model->created_at) ?></p>

                <p class="mt-3 mb-1 text-muted">Total a Pagar</p>
                <p class="fw-bold fs-4"><?= number_format($model->total, 2) ?> €</p>
            </div>
        </div>

    </div>
</div>

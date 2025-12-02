<?php

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Widget para exibir métodos de pagamento como radio buttons estilizados
 * 
 * Uso:
 * <?= PaymentMethodWidget::widget([
 *     'form' => $form,
 *     'model' => $model,
 *     'attribute' => 'metodospagamentos_id',
 *     'metodos' => $metodos,
 * ]) ?>
 */
class PaymentMethodWidget extends Widget
{
    /**
     * @var \yii\widgets\ActiveForm O formulário ativo
     */
    public $form;

    /**
     * @var \yii\db\ActiveRecord O modelo
     */
    public $model;

    /**
     * @var string O atributo do modelo (ex: 'metodospagamentos_id')
     */
    public $attribute;

    /**
     * @var array Lista de métodos de pagamento
     */
    public $metodos = [];

    /**
     * @var array Mapeamento de ícones por nome de método
     */
    public $icons = [
        'Multibanco' => 'university',
        'MB Way' => 'mobile-alt',
        'Cartão de Crédito' => 'credit-card',
        'Dinheiro' => 'money-bill-wave',
        'Transferência Bancária' => 'exchange-alt',
    ];

    /**
     * @var string Ícone padrão se não encontrar correspondência
     */
    public $defaultIcon = 'wallet';

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->form->field($this->model, $this->attribute)->radioList(
            \yii\helpers\ArrayHelper::map($this->metodos, 'id', 'nome'),
            [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $icon = $this->icons[$label] ?? $this->defaultIcon;
                    
                    return '
                    <div class="form-check mb-3">
                        <input type="radio" 
                               name="' . $name . '" 
                               value="' . $value . '" 
                               class="form-check-input payment-method-radio" 
                               id="metodo-' . $value . '"
                               ' . ($checked ? 'checked' : '') . '>
                        <label class="form-check-label w-100" for="metodo-' . $value . '" style="cursor: pointer;">
                            <div class="payment-method-option d-flex align-items-center p-3 border rounded ' . ($checked ? 'border-primary bg-light' : '') . '">
                                <i class="fas fa-' . $icon . ' fa-2x text-primary me-3"></i>
                                <div>
                                    <strong>' . Html::encode($label) . '</strong>
                                </div>
                            </div>
                        </label>
                    </div>';
                },
                'encode' => false,
            ]
        )->label(false);
    }
}

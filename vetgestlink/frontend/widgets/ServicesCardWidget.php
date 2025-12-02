<?php

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Widget para exibir cards de serviços com ícones customizáveis
 * 
 * Uso:
 * <?= ServicesCardWidget::widget([
 *     'servicos' => $servicos,
 *     'colors' => ['#4CB88A', '#94E2B6'],
 *     'icons' => ['fa-syringe', 'fa-stethoscope'],
 * ]) ?>
 */
class ServicesCardWidget extends Widget
{
    /**
     * @var array Lista de serviços a exibir
     */
    public $servicos = [];

    /**
     * @var array Cores para alternar nos cards (hex)
     */
    public $colors = ['#4CB88A', '#94E2B6', '#6FD4A8'];

    /**
     * @var array Ícones Font Awesome para alternar (sem 'fas')
     */
    public $icons = [
        'fa-syringe', 
        'fa-stethoscope', 
        'fa-heartbeat', 
        'fa-capsules', 
        'fa-x-ray', 
        'fa-microscope'
    ];

    /**
     * @var string Classes CSS adicionais para o card
     */
    public $cardClass = 'border-0 shadow-sm h-100 text-center';

    /**
     * @var string Classes CSS para as colunas
     */
    public $colClass = 'col-lg-3 col-md-4 col-sm-6';

    /**
     * @var bool Mostrar o valor do serviço
     */
    public $showValue = true;

    /**
     * @var string Tamanho do ícone (fa-lg, fa-2x, fa-3x, etc)
     */
    public $iconSize = 'fa-3x';

    public function run()
    {
        if (empty($this->servicos)) {
            return $this->renderEmpty();
        }

        $html = '';
        foreach ($this->servicos as $index => $servico) {
            $color = $this->colors[$index % count($this->colors)];
            $icon = $this->icons[$index % count($this->icons)];
            
            $html .= $this->renderCard($servico, $color, $icon);
        }

        return $html;
    }

    protected function renderCard($servico, $color, $icon)
    {
        $nome = Html::encode($servico->nome);
        $valorHtml = '';
        
        if ($this->showValue) {
            $valor = \Yii::$app->formatter->asDecimal($servico->valor, 2);
            $valorHtml = Html::tag('p', 
                Html::tag('i', '', ['class' => 'fas fa-euro-sign']) . ' ' .
                Html::tag('strong', $valor . '€'),
                ['class' => 'text-muted mb-3']
            );
        }

        $cardBody = Html::tag('i', '', ['class' => "fas {$icon} {$this->iconSize} mb-3", 'style' => "color: {$color};"]) .
                    Html::tag('h5', $nome, ['class' => 'fw-bold mb-2', 'style' => "color: {$color};"]) .
                    $valorHtml;

        $card = Html::tag('div', 
            Html::tag('div', $cardBody, ['class' => 'card-body']),
            ['class' => "card {$this->cardClass}"]
        );

        return Html::tag('div', $card, ['class' => $this->colClass]);
    }

    protected function renderEmpty()
    {
        return Html::tag('div',
            Html::tag('div',
                Html::tag('i', '', ['class' => 'fas fa-info-circle me-2']) . 
                'Nenhum serviço disponível no momento.',
                ['class' => 'alert alert-info text-center']
            ),
            ['class' => 'col-12']
        );
    }
}

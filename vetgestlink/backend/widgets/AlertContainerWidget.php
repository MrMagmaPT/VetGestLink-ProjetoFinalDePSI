<?php

namespace backend\widgets;

use common\widgets\Alert;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class AlertContainerWidget extends Widget
{
    public $text = 'Card Title';
    public $options = [];
    public function run()
    {
        $output = <<<HTML
            <div class="card shadow-sm mt-3">
                <div class="card-header"><h3 class="card-title">{$this->text}</h3></div>
                <div class="card-body">
        HTML;
        $tamanho = count($this->options);
        foreach ($this->options as $line) {
            $output .= AlertWidget::widget($line);
            if ($tamanho > 1) {
                $output .= "<br>";
                $tamanho--;
            }
        }
        $output .= <<<HTML
                </div>
            </div
        HTML;
        return $output;
    }
}
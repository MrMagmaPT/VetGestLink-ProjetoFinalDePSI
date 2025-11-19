<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class QuickActionContainerWidget extends Widget
{
    public $text = 'Card Title';
    public $options = [];

    public function run()
    {
        $output = <<<HTML
            <div class="card shadow-sm">
                <div class="card-header"><h3 class="card-title">{$this->text}</h3></div>
                <div class="card-body">
        HTML;
        foreach ($this->options as $line) {
            $output .= QuickActionWidget::widget($line);
        }
        $output .= <<<HTML
                </div>
            </div>
        HTML;
        return $output;
    }
}
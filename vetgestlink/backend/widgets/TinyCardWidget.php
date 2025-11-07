<?php

namespace backend\widgets;

use yii\base\Widget;

class TinyCardWidget extends Widget
{
    public $value = 0;
    public $text = 'TEXTINHO';
    public $icon = 'fa-exclamation-triangle';
    public $iconColorClass = 'icon-red';

    public function run()
    {
        return <<<HTML
            <div class="col-md-4">
                <div class="stock-card">
                    <div class="stock-icon {$this->iconColorClass}"><i class="fas {$this->icon}"></i></div>
                    <h2 class="mb-0">{$this->value}</h2>
                    <p class="text-muted">{$this->text}</p>
                </div>
            </div>
        HTML;
    }
}

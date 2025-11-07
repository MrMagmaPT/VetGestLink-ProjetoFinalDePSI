<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class CardsContainerWidget extends Widget
{
    public $buttonclass = 'btn btn-sm btn-primary';
    public $buttontext = 'Ver Tudo';
    public $text = 'Card Title';
    public $url = '/';
    public $cards = [];

    public function run()
    {
        $encoded_url = Url::to([$this->url]);
        $linkHtml = Html::a($this->buttontext, $encoded_url, ['class' => $this->buttonclass]);

        $output = <<<HTML
            <div class="col-lg-8 col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">{$this->text}</h3>
                        <div class="card-tools">
                            {$linkHtml}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
        HTML;
        foreach ($this->cards as $card) {
            $output .= TinyCardWidget::widget($card);
        }
        $output .= <<<HTML
                        </div>
                    </div>
                </div>
            </div>
        HTML;

        return $output;
    }
}

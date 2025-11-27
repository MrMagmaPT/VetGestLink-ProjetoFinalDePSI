<?php
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuItem extends Widget
{
    public $label;
    public $url = '#';
    public $active = false;
    public $encodeLabel = false;

    public function run()
    {
        $linkClass = trim('nav-link ' . ($this->active ? 'active' : ''));

        $labelHtml = $this->encodeLabel ? Html::encode($this->label) : $this->label;
        $content = $labelHtml;

        $a = Html::a($content, Url::to($this->url),array_merge(['class' => $linkClass]));
        return Html::tag('li', $a, array_merge(['class' => 'nav-item']));
    }
}

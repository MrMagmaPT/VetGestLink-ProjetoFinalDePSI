<?php
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuItem extends Widget
{
    public $icon;
    public $text;
    public $url = '#';
    public $active = false;

    public function run()
    {
        $linkClass = trim('nav-link ' . ($this->active ? 'active' : ''));

        $label = "<i class='nav-icon {$this->icon}'></i><p>{$this->text}</p>";



        $a = Html::a($label, Url::to($this->url),array_merge(['class' => $linkClass]));
        return Html::tag('li', $a, array_merge(['class' => 'nav-item']));
    }
}

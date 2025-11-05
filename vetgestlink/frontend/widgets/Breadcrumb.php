<?php

namespace frontend\widgets;

use yii\base\Widget;

class Breadcrumb extends Widget
{
    public $title = 'Página';
    public $backgroundImage = '/assets/img/hero/h1_hero.jpg';
    public $items = [];

    public function run()
    {
        return $this->render('breadcrumb', [
            'title' => $this->title,
            'backgroundImage' => $this->backgroundImage,
            'items' => $this->items,
        ]);
    }
}


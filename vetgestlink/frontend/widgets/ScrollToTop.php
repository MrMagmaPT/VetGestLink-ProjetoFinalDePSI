<?php

namespace frontend\widgets;

use yii\base\Widget;

class ScrollToTop extends Widget
{
    public function run()
    {
        return $this->render('scroll-to-top');
    }
}


<?php

namespace frontend\widgets;

use yii\base\Widget;

class Preloader extends Widget
{
    public $logoPath = '/static/img/logo/logo.png';

    public function run()
    {
        return $this->render('preloader', [
            'logoPath' => $this->logoPath,
        ]);
    }
}

<?php

namespace frontend\widgets;

use yii\base\Widget;
use yii\web\View;

class ThemeAssets extends Widget
{
    public $cssFiles = [];
    public $useDefaultCss = true;

    private $defaultCssFiles = [
        'bootstrap.min.css',
        'owl.carousel.min.css',
        'slicknav.css',
        'flaticon.css',
        'animate.min.css',
        'magnific-popup.css',
        'fontawesome-all.min.css',
        'themify-icons.css',
        'slick.css',
        'nice-select.css',
        'style.css',
    ];

    public function run()
    {
        $files = $this->useDefaultCss ? $this->defaultCssFiles : $this->cssFiles;

        foreach ($files as $file) {
            $filePath = \Yii::getAlias('@webroot') . '/static/css/' . $file;
            $version = is_file($filePath) ? filemtime($filePath) : time();
            
            $this->view->registerCssFile(
                \Yii::getAlias('@web') . '/static/css/' . $file . '?v=' . $version,
                ['depends' => [\yii\web\JqueryAsset::class]]
            );
        }
    }
}

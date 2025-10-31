<?php

namespace frontend\widgets;

use yii\base\Widget;
use yii\web\View;

class ThemeScripts extends Widget
{
    public $jsFiles = [];
    public $useDefaultScripts = true;

    private $defaultJsFiles = [
        'vendor/modernizr-3.5.0.min.js', 'vendor/jquery-3.6.0.min.js', // jQuery primeiro
        'popper.min.js',
        'bootstrap.min.js',
        'jquery.slicknav.min.js',
        'owl.carousel.min.js',
        'slick.min.js', // Slick DEVE vir antes do main.js
        'wow.min.js',
        'animated.headline.js',
        'jquery.magnific-popup.js',
        'jquery.nice-select.min.js',
        'jquery.sticky.js',
        'jquery.validate.min.js',
        'contact.js',
        'jquery.form.js',
        'mail-script.js',
        'jquery.ajaxchimp.min.js',
        'plugins.js',
        'main.js', // main.js DEVE vir depois de slick.min.js
    ];

    public function run()
    {
        $files = $this->useDefaultScripts ? $this->defaultJsFiles : $this->jsFiles;

        foreach ($files as $file) {
            $this->view->registerJsFile(
                \Yii::getAlias('@web') . '/static/js/' . $file,
                ['depends' => [\yii\web\JqueryAsset::class]]
            );
        }
    }
}

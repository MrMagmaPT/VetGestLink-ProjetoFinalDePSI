<?php
namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static';

    public function init()
    {
        parent::init();

        // Automatically load all CSS files (except .map)
        foreach (glob(Yii::getAlias('@webroot/static/css/*.css')) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                $this->css[] = 'css/' . basename($file);
            }
        }

        // Automatically load all font files
        foreach (glob(Yii::getAlias('@webroot/static/fonts/*')) as $file) {
            if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['woff', 'woff2', 'ttf', 'otf', 'eot'])) {
                $this->publishOptions['fonts'][] = 'fonts/' . basename($file);
            }
        }
    }

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}

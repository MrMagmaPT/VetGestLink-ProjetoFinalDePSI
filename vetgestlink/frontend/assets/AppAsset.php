<?php
namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function init()
    {
        parent::init();

        // Automatically load all CSS files (except .map)
        foreach (glob(Yii::getAlias('@webroot/assets/css/*.css')) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                $this->css[] = 'assets/css/' . basename($file);
            }
        }

        // Automatically load all JS files (except .map)
        foreach (glob(Yii::getAlias('@webroot/assets/js/*.js')) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {
                $this->js[] = 'assets/js/' . basename($file);
            }
        }
    }

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}

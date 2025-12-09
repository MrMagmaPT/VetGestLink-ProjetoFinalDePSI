<?php
namespace common\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle
{
    public $sourcePath = '@common/assets';
    public $js = [
        'js/image-preview.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $publishOptions = [
        'only' => [
            'js/*',
        ],
    ];
}

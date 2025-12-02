<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class Preloader extends Widget
{
    // if not provided, will use '@logo' alias defined in frontend config
    public $logoPath = null;
    public $logoUrl = null; // computed in init

    public function run()
    {
        $logoUrl = $this->getLogoUrl();
        return $this->render('preloader', [
            'logoUrl' => $logoUrl,
        ]);
    }

    protected function getLogoUrl()
    {
        // fallback to static path; compute URL and file path at runtime
        $logoPath = $this->logoPath ?? '/static/img/logo/logo.png';
        $path = Yii::getAlias('@webroot') . $logoPath;
        if (is_file($path)) {
            $version = filemtime($path);
        } else {
            $version = time();
        }
        return Yii::getAlias('@web') . $logoPath . '?v=' . $version;
    }
}

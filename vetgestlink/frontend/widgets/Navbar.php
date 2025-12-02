<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class Navbar extends Widget
{
    public $logoPath = null; // use @logo alias by default
    public $menuItems = [];
    public $logoUrl = null; // computed in run

    public function init()
    {
        parent::init();

        if (empty($this->menuItems)) {
            $this->menuItems = $this->getDefaultMenuItems();
        }
    }

    private function getDefaultMenuItems()
    {

        $user = Yii::$app->user->identity;
        $profileId = $user->userprofiles->id ?? null;
        $items = [
            ['label' => 'Sobre', 'url' => ['site/about']],
            ['label' => 'Contact', 'url' => ['site/contact']],
        ];

        if (!Yii::$app->user->isGuest) {
            $items = array_merge($items, [
                ['label' => 'Pagamentos & Fatura', 'url' => ['fatura/index']],
                ['label' => 'Animal', 'url' => ['animal/index']],
                ['label' => 'Marcações', 'url' => ['marcacao/index']],
                ['label' => 'Perfil - ' . $user->username, 'url' => ['userprofile/view', 'id' => $profileId]],
            ]);
        }

        return $items;
    }

    public function run()
    {
        $logoUrl = $this->getLogoUrl();
        return $this->render('navbar', [
            'logoUrl' => $logoUrl,
            'menuItems' => $this->menuItems,
        ]);
    }

    protected function getLogoUrl()
    {
        $logoPath = $this->logoPath ?? '/static/img/logo/logo.png';
        $path = Yii::getAlias('@webroot') . $logoPath;
        $version = (is_file($path) ? filemtime($path) : time());
        return Yii::getAlias('@web') . $logoPath . '?v=' . $version;
    }
}


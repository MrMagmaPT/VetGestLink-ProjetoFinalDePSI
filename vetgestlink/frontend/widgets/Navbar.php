<?php

namespace frontend\widgets;

use yii\base\Widget;
use Yii;

class Navbar extends Widget
{
    public $logoPath = '/static/img/logo/logo.png'; // Updated to correct path
    public $menuItems = [];

    public function init()
    {
        parent::init();

        if (empty($this->menuItems)) {
            $this->menuItems = $this->getDefaultMenuItems();
        }
    }

    private function getDefaultMenuItems()
    {
        $items = [
            ['label' => 'Sobre', 'url' => ['site/about']],
            ['label' => 'Contact', 'url' => ['site/contact']],
        ];

        if (!Yii::$app->user->isGuest) {
            $items = array_merge($items, [
                ['label' => 'Pagamentos & Faturas', 'url' => ['faturas/index']],
                ['label' => 'Animais', 'url' => ['animais/index']],
                ['label' => 'Marcações', 'url' => ['marcacoes/index']],
                ['label' => 'Perfil - ' . Yii::$app->user->identity->username, 'url' => ['user-profile/index']],
            ]);
        }

        return $items;
    }

    public function run()
    {
        return $this->render('navbar', [
            'logoPath' => $this->logoPath,
            'menuItems' => $this->menuItems,
        ]);
    }
}

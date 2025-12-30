<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class Navbar extends Widget
{
    public $logoPath = null; // use @logo alias by default
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
        $userId = Yii::$app->user->identity->id ?? null;
        // Get username safely
        $userName = Yii::$app->user->identity->username ?? null;

        // Define default menu items
        $items = [
            ['label' => 'Sobre', 'url' => ['site/about']],
            ['label' => 'Contatos', 'url' => ['site/contact']],
            ['label' => 'Informações', 'url' => ['site/information']],
        ];

        // Add user-specific items if logged in
        if (!Yii::$app->user->isGuest ) {
            $items = array_merge($items, [
                ['label' => 'Faturas', 'url' => ['fatura/index' , 'userId' ]],
                ['label' => 'Animais', 'url' => ['animal/index' , 'userId' ]],
                ['label' => 'Marcações', 'url' => ['marcacao/index', 'userId' ]],
                ['label' => 'Lembretes', 'url' => ['lembrete/index', 'userId' ]],
                ['label' => 'Perfil', 'url' => ['userprofile/view', 'id' => $userId]],
            ]);
        }

        return $items;
    }

    public function run()
    {
        $logoUrl = Yii::getAlias('@web') . '/static/img/logo/logo.png';
        return $this->render('navbar', [
            'logoUrl' => $logoUrl,
            'menuItems' => $this->menuItems,
        ]);
    }

}


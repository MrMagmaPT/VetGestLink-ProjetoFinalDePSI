<?php

namespace frontend\widgets;

use yii\base\Widget;
use Yii;

class Sidebar extends Widget
{
    public $items = [];
    public $title = 'Menu';

    public function init()
    {
        parent::init();

        if (empty($this->items) && !Yii::$app->user->isGuest) {
            $this->items = $this->getDefaultItems();
        }
    }

    private function getDefaultItems()
    {
        return [
            ['label' => 'Dashboard', 'url' => ['site/index'], 'icon' => 'fa-home'],
            ['label' => 'Meus Animais', 'url' => ['animais/index'], 'icon' => 'fa-paw'],
            ['label' => 'Marcações', 'url' => ['marcacoes/index'], 'icon' => 'fa-calendar-alt'],
            ['label' => 'Faturas', 'url' => ['faturas/index'], 'icon' => 'fa-file-invoice-dollar'],
            ['label' => 'Meu Perfil', 'url' => ['user-profile/index'], 'icon' => 'fa-user'],
        ];
    }

    public function run()
    {
        return $this->render('sidebar', [
            'items' => $this->items,
            'title' => $this->title,
        ]);
    }
}


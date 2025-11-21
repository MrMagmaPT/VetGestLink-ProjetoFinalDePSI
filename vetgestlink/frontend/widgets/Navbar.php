<?php

namespace frontend\widgets;

use yii\base\Widget;
use Yii;

class Navbar extends Widget
{
    public $logoPath = '/static/img/logo/logo.png';
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

        $user = Yii::$app->user->identity;
        $profileId = $user->userprofiles->id ?? null;
        $items = [
            ['label' => 'Sobre', 'url' => ['site/about']],
            ['label' => 'Contact', 'url' => ['site/contact']],
        ];

        if (!Yii::$app->user->isGuest) {

            // tenta obter o perfil (se houver relação user->userprofile em User.php)
            $profileId = $user->userprofile->id ?? null;

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
        return $this->render('navbar', [
            'logoPath' => $this->logoPath,
            'menuItems' => $this->menuItems,
        ]);
    }
}


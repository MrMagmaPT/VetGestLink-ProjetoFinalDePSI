<?php

namespace frontend\widgets;

use yii\base\Widget;

class Footer extends Widget
{
    public $logoPath = '/static/img/logo/logo.png';
    public $companyLinks = [];
    public $serviceLinks = [];
    public $contactInfo = [];

    public function init()
    {
        parent::init();

        if (empty($this->companyLinks)) {
            $this->companyLinks = [
                ['label' => 'Início', 'url' => ['site/index']],
                ['label' => 'Sobre Nós', 'url' => ['site/about']],
                ['label' => 'Serviços', 'url' => ['site/services']],
                ['label' => 'Casos de Sucesso', 'url' => ['#']],
                ['label' => 'Contactos', 'url' => ['site/contact']],
            ];
        }

        if (empty($this->serviceLinks)) {
            $this->serviceLinks = [
                ['label' => 'Consultas Veterinárias', 'url' => ['#']],
                ['label' => 'Cirurgias', 'url' => ['#']],
                ['label' => 'Vacinação', 'url' => ['#']],
                ['label' => 'Internamento', 'url' => ['#']],
                ['label' => 'Análises Clínicas', 'url' => ['#']],
            ];
        }

        if (empty($this->contactInfo)) {
            $this->contactInfo = [
                ['label' => '+351 234 567 890', 'url' => 'tel:+351234567890'],
                ['label' => 'contacto@vetgestlink.com', 'url' => 'mailto:contacto@vetgestlink.com'],
                ['label' => 'Lisboa, Portugal', 'url' => '#'],
            ];
        }
    }

    public function run()
    {
        return $this->render('footer', [
            'logoPath' => $this->logoPath,
            'companyLinks' => $this->companyLinks,
            'serviceLinks' => $this->serviceLinks,
            'contactInfo' => $this->contactInfo,
        ]);
    }
}

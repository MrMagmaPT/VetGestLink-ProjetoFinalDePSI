<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class Footer extends Widget
{
    public $logoPath = null; // use @logo alias by default
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
                // Link para a section de serviços na página inicial
                ['label' => 'Serviços', 'url' => ['/site/index#servicos']],
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
                ['label' => 'vetgestlink@gmail.com', 'url' => 'mailto:vetgestlink@gmail.com'],
                ['label' => 'Leiria, Portugal', 'url' => '#'],
            ];
        }
    }

    public function run()
    {
        $logoUrl = $this->getLogoUrl();
        return $this->render('footer', [
            'logoUrl' => $logoUrl,
            'companyLinks' => $this->companyLinks,
            'serviceLinks' => $this->serviceLinks,
            'contactInfo' => $this->contactInfo,
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

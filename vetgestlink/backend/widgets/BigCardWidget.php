<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class BigCardWidget extends Widget
{
    // Icon que vai ser exibido no card (ex: 'fa-users', 'fa-chart-bar', etc)
    public $icon = 'fa-users';
    //  Class de cor a ser usada pelo icone (ex: 'icon-blue', 'icon-red', etc)
    public $iconColorClass = 'icon-gray';
    // Texto rpincipal exibido no card (ex: "Total Users")
    public $text = 'Card Title';
    // Url para redirecionamento ao clicar no botão de detalhes
    public $url = '/';
    // Valor principal exibido no card (ex: 1500)
    public $value = 0;


    // Rederiza o html do widget e merdas associadas :D
    public function run()
    {
        // Mapa de cores para os cards (small-box)
        $colorMap = [
            'icon-blue' => 'bg-info',
            'icon-yellow' => 'bg-warning',
            'icon-green' => 'bg-success',
            'icon-orange' => 'bg-warning',
            'icon-red' => 'bg-danger',
            'icon-purple' => 'bg-purple',
            'icon-gray' => 'bg-secondary',
            'icon-primary' => 'bg-primary',
            'icon-success' => 'bg-success',
            'icon-secondary' => 'bg-secondary',
            'icon-pink' => 'bg-pink',
        ];

        // Obter a classe de cor do card
        $bgClass = $colorMap[$this->iconColorClass] ?? 'bg-info';

        //url convertido de incompleto (ex: /user/index) para
        // completo usando o yii para n ser nada hardcoded
        // (ex: http://meusite.com/user/index)
        $encoded_url = Url::to([$this->url]);

        //devolve o card com os elementos que acabamos de adicionar/modificar (col-lg-6 para ser maior)
        return <<<HTML
            <div class="col-lg-6 col-12">
                <!-- small box -->
                <div class="small-box {$bgClass}">
                    <div class="inner">
                        <h3>{$this->value}</h3>
                        <p>{$this->text}</p>
                    </div>
                    <div class="icon">
                        <i class="fas {$this->icon}"></i>
                    </div>
                </div>
            </div>
        HTML;
    }
}
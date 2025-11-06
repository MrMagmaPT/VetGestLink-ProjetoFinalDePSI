<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;

class MainCardIndexWidget extends Widget
{
    //valor base a ser usado para o card caso nada seja passado por parametros e os caralhos

    // Icon que vai ser exibido no card (ex: 'fa-users', 'fa-chart-bar', etc)
    public $icon = 'fa-users';
    //  Class de cor a ser usada pelo icone (ex: 'icon-blue', 'icon-red', etc)
    public $iconColorClass = 'icon-gray';
    // Texto rpincipal exibido no card (ex: "Total Users")
    public $text = 'Card Title';
    // valor numerico exibido no card ou texto (ex: 150, 75%, etc)
    public $value = 0;
    // Url para redirecionamento ao clicar no card
    public $url = '/';


    // Rederiza o html do widget e merdas associadas :D
    public function run()
    {
        //url convertido de incompleto (ex: /user/index) para
        // completo usando o yii para n ser nada hardcoded
        // (ex: http://meusite.com/user/index)
        $encoded_url = Url::to([$this->url]);

        //devolve o card com os elementos que acabamos de adicionar/modificar
        return <<<HTML
            <div class="col-lg-6 col-12" style="cursor:pointer;" onclick="window.location.href='{$encoded_url}';">
                <div class="info-box info-box-custom shadow-sm">
                    <span class="info-box-icon {$this->iconColorClass} rounded">
                        <i class="fas {$this->icon}"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">{$this->text}</span>
                        <span class="info-box-number">{$this->value}</span>
                    </div>
                </div>
            </div>
        HTML;

    }
}

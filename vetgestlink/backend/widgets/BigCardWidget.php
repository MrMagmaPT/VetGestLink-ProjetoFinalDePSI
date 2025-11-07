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
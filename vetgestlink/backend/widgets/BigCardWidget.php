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
        
        // Mapeamento de cores(AS cores podem ser ajustadas conforme necessário)//WILSON
        $colorMap = [
            'icon-blue' => 'background: #007bff; color: white;',
            'icon-yellow' => 'background: #ffc107; color: white;',
            'icon-green' => 'background: #28a745; color: white;',
            'icon-orange' => 'background: #ff9800; color: white;',
            'icon-red' => 'background: #dc3545; color: white;',
            'icon-purple' => 'background: #9333ea; color: white;',
            'icon-gray' => 'background: #6c757d; color: white;',
        ];
        
        $iconStyle = $colorMap[$this->iconColorClass] ?? 'background: #6c757d; color: white;';

        //devolve o card com os elementos que acabamos de adicionar/modificar
        return <<<HTML
            <style>
            .info-box-custom {
                border-radius: 20px !important;
                box-shadow: 0 2px 16px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.06);
                background: #f8fafc;
                padding: 20px 16px 16px 16px;
                transition: box-shadow 0.2s, transform 0.2s;
            }
            .info-box-custom:hover {
                box-shadow: 0 8px 32px rgba(0,0,0,0.14), 0 2px 12px rgba(0,0,0,0.10);
                transform: translateY(-2px) scale(1.02);
            }
            .info-box-icon.rounded {
                border-radius: 14px !important;
                font-size: 2.4rem;
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 16px;
            }
            .info-box-content {
                padding-left: 8px;
            }
            .info-box-text {
                font-size: 1.1rem;
                font-weight: 600;
                color: #343a40;
            }
            .info-box-number {
                font-size: 1.9rem;
                font-weight: 700;
                color: #007bff;
            }
            </style>
            <div class="col-lg-6 col-12" style="cursor:pointer;" onclick="window.location.href='{$encoded_url}';">
                <div class="info-box info-box-custom shadow-sm">
                    <span class="info-box-icon rounded" style="{$iconStyle}">
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
<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class SmallCardWidget extends Widget
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
        // Mapa de cores para os ícones
        $colorMap = [
            'icon-blue' => 'background: #007bff; color: white;',
            'icon-yellow' => 'background: #ffc107; color: white;',
            'icon-green' => 'background: #28a745; color: white;',
            'icon-orange' => 'background: #ff9800; color: white;',
            'icon-red' => 'background: #dc3545; color: white;',
            'icon-purple' => 'background: #9333ea; color: white;',
            'icon-gray' => 'background: #6c757d; color: white;',
            'icon-primary' => 'background: #007bff; color: white;',
            'icon-success' => 'background: #28a745; color: white;',
            'icon-secondary' => 'background: #6c757d; color: white;',
            'icon-pink' => 'background: #e91e63; color: white;',
        ];

        // Obter o estilo inline para o ícone
        $iconStyle = $colorMap[$this->iconColorClass] ?? 'background: #6c757d; color: white;';

        //url convertido de incompleto (ex: /user/index) para
        // completo usando o yii para n ser nada hardcoded
        // (ex: http://vetgestlink/user/index)
        $encoded_url = Url::to([$this->url]);

        //devolve o card com os elementos que acabamos de adicionar/modificar
        return <<<HTML
            <style>
            .info-box-custom {
                border-radius: 28px !important;
                box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.08);
                background: #f8fafc;
                padding: 24px 18px 18px 18px;
                transition: box-shadow 0.2s, transform 0.2s;
            }
            .info-box-custom:hover {
                box-shadow: 0 10px 40px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.14);
                transform: translateY(-3px) scale(1.04);
            }
            .info-box-icon.rounded {
                border-radius: 18px !important;
                font-size: 2.6rem;
                width: 64px;
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 18px;
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
                font-size: 2rem;
                font-weight: 700;
                color: #007bff;
            }
            </style>
            <div class="col-lg-3 col-12" style="cursor:pointer;" onclick="window.location.href='{$encoded_url}';">
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
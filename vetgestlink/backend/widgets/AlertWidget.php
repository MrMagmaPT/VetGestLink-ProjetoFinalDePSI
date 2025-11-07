<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class AlertWidget extends Widget
{
    public $title = 'TITULO ALERTA';
    public $message = 'mensagem de alerta';
    public $icon = 'fa-exclamation-circle';
    public $class = 'text-danger';

    public function run()
    {
        $message = <<<HTML
            <div class="alert-custom">
                <i class="fas {$this->icon} {$this->class}"></i>
                <strong>{$this->title}</strong><br>
                <small>{$this->message}</small>
            </div>
        HTML;

        return $message;
    }
}
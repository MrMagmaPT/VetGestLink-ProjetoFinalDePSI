<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class AlertContainerWidget extends Widget
{
    public $text = 'Card Title';
    public $options = [];

    public function run()
    {
        $output = <<<HTML
            <div class="card shadow-sm mt-3">
                <div class="card-header"><h3 class="card-title">{$this->text}</h3></div>
                <div class="card-body">
        HTML;

        foreach ((array)$this->options as $opt) {
            // Novo formato: um item com 'content' (string ou lista de alertas)
            if (array_key_exists('content', $opt)) {
                $icon = $opt['icon'] ?? 'fa-exclamation-circle';
                $class = $opt['class'] ?? 'text-danger';
                $content = $opt['content'];

                if (is_array($content)) {
                    $remaining = count($content);
                    foreach ($content as $alert) {
                        if (!is_array($alert)) {
                            continue;
                        }

                        $title = isset($alert['title']) ? Html::encode($alert['title']) : 'TITULO ALERTA';
                        $message = isset($alert['content']) ? Html::encode($alert['content']) : '';

                        $output .= AlertWidget::widget([
                            'title' => $title,
                            'message' => $message,
                            'icon' => $icon,
                            'class' => $class,
                        ]);

                        if (--$remaining > 0) {
                            $output .= "<br>";
                        }
                    }
                } elseif (is_string($content) && $content !== '') {
                    $title = isset($opt['title']) ? Html::encode($opt['title']) : 'TITULO ALERTA';
                    $output .= AlertWidget::widget([
                        'title' => $title,
                        'message' => Html::encode($content),
                        'icon' => $icon,
                        'class' => $class,
                    ]);
                }
                continue;
            }

            // Formato antigo: cada linha já é config de AlertWidget deprecated
            $output .= AlertWidget::widget($opt);
            $output .= "<br>";
        }

        $output .= <<<HTML
                </div>
            </div>
        HTML;

        return $output;
    }
}

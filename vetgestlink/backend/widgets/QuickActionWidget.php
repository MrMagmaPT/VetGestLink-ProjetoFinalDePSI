<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class QuickActionWidget extends Widget
{
    public $text = 'Card Title';
    public $icon = 'fa-user-plus';
    public $url = '/userprofile/create';

    public function run()
    {
        // Generate the URL string
        $encoded_url = Url::to([$this->url]);

        // Generate the anchor tag HTML with icon and text
        $linkHtml = Html::a("<i class=\"fas {$this->icon}\"></i> {$this->text}", $encoded_url, ['class' => 'quick-action-btn']);

        // Return the anchor tag HTML directly
        return $linkHtml;
    }
}
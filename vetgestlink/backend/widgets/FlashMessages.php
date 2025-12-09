<?php
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class FlashMessages extends Widget
{
    public function run()
    {
        $output = '';
        foreach (\Yii::$app->session->getAllFlashes() as $type => $messages) {
            $alertType = ($type === 'error') ? 'danger' : $type;
            $messages = (array) $messages;
            $output .= '<div class="alert alert-' . $alertType . ' alert-dismissible fade show" role="alert">';
            $output .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            if (count($messages) > 1) {
                $output .= '<ul class="mb-0">';
                foreach ($messages as $message) {
                    $output .= '<li>' . Html::encode($message) . '</li>';
                }
                $output .= '</ul>';
            } else {
                $output .= Html::encode($messages[0]);
            }
            $output .= '</div>';
        }
        return $output;
    }
}

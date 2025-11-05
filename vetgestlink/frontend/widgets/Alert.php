<?php

namespace frontend\widgets;

use yii\base\Widget;
use Yii;

class Alert extends Widget
{
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $result = '';

        foreach ($flashes as $type => $flash) {
            if (!in_array($type, ['error', 'danger', 'success', 'info', 'warning'])) {
                continue;
            }

            $messages = (array) $flash;
            foreach ($messages as $message) {
                $result .= $this->render('alert', [
                    'type' => $type,
                    'message' => $message,
                ]);
            }

            $session->removeFlash($type);
        }

        return $result;
    }
}


<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    /**
     * GET /api
     * Retorna informações sobre a API
     */
    public function actionIndex()
    {
        return [
            'name' => 'API VetGestLink',
            'version' => '1.0.0',
            'status' => 'online',
            'endpoints' => [
                '/api/auth/login' => 'autenticação de utilizadores',
                '/api/auth/logout' => 'autenticação de utilizadores',

            ],
        ];
    }

}

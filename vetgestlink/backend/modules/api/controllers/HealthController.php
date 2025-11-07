<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Controller de Health Check
 *
 * Endpoint público para verificar status do servidor.
 */
class HealthController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // REMOVER autenticação (endpoint público)
        unset($behaviors['authenticator']);

        // CORS
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        // JSON response
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * GET /health
     * Verifica status do servidor
     */
    public function actionIndex()
    {
        return [
            'status' => 'ok',
            'message' => 'Servidor funcionando corretamente',
            'timestamp' => date('Y-m-d\TH:i:s\Z'),
            'version' => '1.0.0',
        ];
    }
}


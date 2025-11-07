<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Controller de Autenticação
 * 
 * Endpoints públicos para login, logout e recuperação de senha.
 * Utiliza o componente AuthComponent para toda a lógica de autenticação.
 */
class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // REMOVER COMPLETAMENTE qualquer autenticação
        unset($behaviors['authenticator']);
        unset($behaviors['rateLimiter']);

        // Adicionar CORS para aceitar requisições de qualquer origem
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        // Forçar resposta em JSON para todos os endpoints
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * Login de cliente
     *
     * POST /auth/login
     * Body: {"username": "carlos", "password": "senha123"}
     *
     * @return array
     */
    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Aceitar dados do body (JSON) ou query params
        $data = Yii::$app->request->post();
        if (empty($data)) {
            $data = Yii::$app->request->get();
        }

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        try {
            // Delegar ao componente de autenticação
            $result = $this->module->auth->login($username, $password);

            return $result;

        } catch (\yii\web\UnauthorizedHttpException $e) {
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];

        } catch (\yii\web\ForbiddenHttpException $e) {
            Yii::$app->response->statusCode = 403;
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];

        } catch (\Exception $e) {
            Yii::error("Login error: " . $e->getMessage(), __METHOD__);
            Yii::$app->response->statusCode = 500;
            return [
                'success' => false,
                'message' => 'Erro interno do servidor'
            ];
        }
    }

    /**
     * Logout de cliente
     *
     * POST /auth/logout?access-token=xxx
     *
     * @return array
     */
    public function actionLogout()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $token = Yii::$app->request->get('access-token');

        // Delegar ao componente de autenticação
        $result = $this->module->auth->logout($token);

        if (!$result['success']) {
            Yii::$app->response->statusCode = 400;
        }

        return $result;
    }

    /**
     * Recuperar senha
     *
     * POST /auth/forgot-password
     * Body: {"username": "carlos"}
     *
     * @return array
     */
    public function actionForgotPassword()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();
        $username = $data['username'] ?? null;

        // Delegar ao componente de autenticação
        $result = $this->module->auth->requestPasswordReset($username);

        if (!$result['success']) {
            Yii::$app->response->statusCode = 400;
        }

        return $result;
    }

    /**
     * Validar token (endpoint auxiliar para debug/testes)
     *
     * GET /auth/validate-token?access-token=xxx
     *
     * @return array
     */
    public function actionValidateToken()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $token = Yii::$app->request->get('access-token');

        if (!$token) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Token não fornecido'
            ];
        }

        $isValid = $this->module->auth->isValidToken($token);

        if (!$isValid) {
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'message' => 'Token inválido ou expirado'
            ];
        }

        $userInfo = $this->module->auth->getUserInfo($token);

        return [
            'success' => true,
            'message' => 'Token válido',
            'user' => $userInfo
        ];
    }
}


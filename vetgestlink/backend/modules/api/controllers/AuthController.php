<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Controller de Autenticação
 * 
 * Endpoint público para login.
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
     * POST /api/auth/login
     * Body: {"username": "wilson", "password": "12345678"}
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

        // Log da tentativa de login
        Yii::info("Login attempt for username: " . ($username ?? 'null'), __METHOD__);

        try {
            // Delegar ao componente de autenticação
            $result = $this->module->auth->login($username, $password);

            Yii::info("Login successful for: {$username}", __METHOD__);
            return $result;

        } catch (\yii\web\UnauthorizedHttpException $e) {
            Yii::warning("Login unauthorized: " . $e->getMessage() . " for user: {$username}", __METHOD__);
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];

        } catch (\yii\web\ForbiddenHttpException $e) {
            Yii::warning("Login forbidden: " . $e->getMessage() . " for user: {$username}", __METHOD__);
            Yii::$app->response->statusCode = 403;
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];

        } catch (\Exception $e) {
            // Log detalhado do erro
            Yii::error([
                'message' => 'Login error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'username' => $username,
            ], __METHOD__);

            Yii::$app->response->statusCode = 500;

            // Em desenvolvimento, retornar mais detalhes
            if (YII_DEBUG) {
                return [
                    'success' => false,
                    'message' => 'Erro interno do servidor',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Erro interno do servidor'
            ];
        }
    }

    /**
     * Logout de cliente
     *
     * POST /api/auth/logout
     * Aceita token via: Header Authorization, body ou query params
     *
     * @return array
     */
    public function actionLogout()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $token = $this->extractToken();

        if (!$token) {
            Yii::$app->response->statusCode = 400;
            return ['success' => false, 'message' => 'Token não fornecido'];
        }

        return $this->module->auth->logout($token);
    }

    /**
     * Extrai token do header, body ou query params
     */
    private function extractToken()
    {
        // Header Authorization: Bearer <token> que é o auth_key
        $authHeader = Yii::$app->request->headers->get('Authorization');

        //Verificar se o header está no formato Bearer e extrair o token
        if ($authHeader && preg_match('/^Bearer\s+(.*?)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        // Para Query params
        $data = Yii::$app->request->post();
        return $data['token'] ?? Yii::$app->request->getQueryParam('access-token');
    }

    /**
     * Obter informações do usuário autenticado
     *
     * GET /api/auth/profile
     * Header: Authorization: Bearer <token>
     *
     * @return array
     */
    public function actionProfile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $token = $this->extractToken();

        if (!$token) {
            Yii::$app->response->statusCode = 401;
            return ['success' => false, 'message' => 'Token não fornecido'];
        }

        $userInfo = $this->module->auth->getUserInfo($token);

        // Se token inválido
        if (!$userInfo) {
            Yii::$app->response->statusCode = 401;
            return ['success' => false, 'message' => 'Token inválido ou expirado'];
        }

        return ['success' => true, 'user' => $userInfo];
    }

    /**
     * Solicitar recuperação de senha
     *
     * POST /api/auth/forgot
     * Body: {"email": "maria@example.com"}
     *
     * @return array
     */
    public function actionForgot()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();
        $email = $data['email'] ?? null;

        if (!$email) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Email é obrigatório'
            ];
        }

        $result = $this->module->auth->requestPasswordReset($email);
        return $result;
    }
}



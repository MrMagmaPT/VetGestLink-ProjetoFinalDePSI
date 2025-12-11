<?php
namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use common\models\User;

/**
 * Controller de Autenticação
 * 
 * Endpoints públicos para login, logout e recuperação de senha.
 * Este controller NÃO requer autenticação (exceto logout).
 */
class AuthController extends ActiveController
{
    public $modelClass = 'common\models\User';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // CORS - DEVE vir PRIMEIRO
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        // Remover autenticação padrão - endpoints públicos
        unset($behaviors['authenticator']);

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
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        
        // Desabilitar actions padrão do ActiveController
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        
        return $actions;
    }

    /**
     * POST /auth/login
     * Login de cliente
     *
     * Body: {"username": "wilson", "password": "12345678"}
     */
    public function actionLogin()
    {
        $data = Yii::$app->request->post();

        // Validar parâmetros
        if (empty($data['username']) || empty($data['password'])) {
            throw new BadRequestHttpException('Username e password são obrigatórios');
        }

        $username = $data['username'];
        $password = $data['password'];

        // Buscar usuário
        $user = User::findByUsername($username);

        // Validar existência e senha
        if (!$user || !$user->validatePassword($password)) {
            Yii::error("Login falhou - Credenciais inválidas para: {$username}", __METHOD__);
            throw new UnauthorizedHttpException('Credenciais inválidas');
        }

        // Verificar status do usuário
        if ($user->status != User::STATUS_ACTIVE) {
            Yii::error("Login falhou - Conta inativa para: {$username}", __METHOD__);
            throw new ForbiddenHttpException('Conta inativa');
        }

        // Verificar se o usuário tem a role de cliente (APENAS se RBAC estiver configurado)
        $auth = Yii::$app->authManager;
        if ($auth) {
            if (!$auth->checkAccess($user->id, 'cliente')) {
                Yii::warning("Login sem a role 'cliente' para o usuário: {$username}", __METHOD__);
                throw new ForbiddenHttpException(
                    'Acesso negado. Apenas clientes podem usar a aplicação mobile.'
                );
            }
        } else {
            Yii::warning("RBAC não configurado - ignorando verificação de role para o usuário: {$username}", __METHOD__);
        }
        
        // Buscar userprofile
        $userprofile = $user->userprofile;

        // Validar existência do userprofile
        if (!$userprofile) {
            Yii::error("Perfil de usuário não encontrado para o usuário: {$username}", __METHOD__);
            throw new ForbiddenHttpException('Perfil de usuário não encontrado');
        }

        // Log de sucesso
        Yii::info("Login bem-sucedido para o usuário: {$username} (ID: {$user->id})", __METHOD__);

        // Retornar dados do usuário
        return [
            'success' => true,
            'message' => 'Login bem-sucedido',
            'token' => $user->auth_key,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
        ];
    }

    /**
     * POST /auth/logout
     * Logout de cliente (invalida o token)
     *
     * Query: ?access-token=...
     */
    public function actionLogout()
    {
        // Buscar token do query param ou header
        $token = Yii::$app->request->get('access-token');
        
        // Se não veio no query param, tentar no header Authorization
        if (!$token) {
            $authHeader = Yii::$app->request->headers->get('Authorization');
            if ($authHeader && preg_match('/^Bearer\s+(.*?)$/i', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        if (empty($token)) {
            throw new BadRequestHttpException('Token não fornecido');
        }

        // Buscar usuário pelo token
        $user = User::findIdentityByAccessToken($token);

        // Se usuário não encontrado, já está deslogado
        if (!$user) {
            return [
                'success' => true,
                'message' => 'Sessão já encerrada'
            ];
        }

        // // Invalidar token gerando novo
        // $user->generateAuthKey();
        // if ($user->save(false)) {
        //     Yii::info("Token invalidado para usuário ID: {$user->id}", __METHOD__);
        // } else {
        //     Yii::error("Falha ao invalidar token para usuário ID: {$user->id}", __METHOD__);
        // }

        return [
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ];
    }

    /**
     * POST /auth/request-password-reset
     * Solicitar recuperação de senha
     *
     * Body: {"email": "maria@example.com"}
     */
    public function actionForgot()
    {
        // Obter dados do POST
        $data = Yii::$app->request->post();

        // Validar email
        if (empty($data['email'])) {
            throw new BadRequestHttpException('Email é obrigatório');
        }

        // Sanitizar email
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestHttpException('Email inválido');
        }

        // Buscar usuário ativo pelo email
        $user = User::findOne([
            'email' => $email,
            'status' => User::STATUS_ACTIVE,
        ]);

        // Verificar se user com esse email existe
        if (!$user) {
            Yii::warning("Password reset requested for non-existent email: {$email}", __METHOD__);
            // Por segurança, não revelar que o email não existe
            throw new BadRequestHttpException('Este email não está registado no sistema.');
        }
        
        // Gerar token de recuperação
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                Yii::error("Failed to save password reset token for: {$email}", __METHOD__);
                throw new BadRequestHttpException('Erro ao processar solicitação');
            }
        }

        // Enviar email
        try {
            $sent = Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' - Recuperação de Senha'])
                ->setTo($user->email)
                ->setSubject('Recuperação de senha - ' . Yii::$app->name)
                ->send();

            if ($sent) {
                Yii::info("Password reset email sent to: {$email}", __METHOD__);
            } else {
                Yii::warning("Failed to send password reset email to: {$email}", __METHOD__);
            }
        } catch (\Exception $e) {
            Yii::error("Email sending error for {$email}: " . $e->getMessage(), __METHOD__);
            // Não retornar erro ao usuário por segurança
        }

        return [
            'success' => true,
            'message' => 'Verifique seu email para instruções de recuperação de senha.'
        ];
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

        return [
            'success' => true,
             'user' => $userInfo
            ];
    }
}



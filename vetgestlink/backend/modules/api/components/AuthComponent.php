<?php

namespace backend\modules\api\components;

use Yii;
use yii\base\Component;
use yii\web\UnauthorizedHttpException;
use yii\web\ForbiddenHttpException;
use common\models\User;

/**
 * Componente de Autenticação para API
 *
 * Este componente gerencia toda a lógica de autenticação da API,
 * incluindo login, logout, validação de tokens e recuperação de senha.
 *
 * Uso no módulo API (ModuleAPI.php):
 * ```php
 * public function init()
 * {
 *     parent::init();
 *     $this->set('auth', [
 *         'class' => 'backend\modules\api\components\AuthComponent',
 *     ]);
 * }
 * ```
 *
 * Uso em controllers:
 * ```php
 * $user = $this->module->auth->login($username, $password);
 * $this->module->auth->logout($token);
 * ```
 */
class AuthComponent extends Component
{
    /**
     * @var string Mensagem de sucesso padrão para login
     */
    public $loginSuccessMessage = 'Login bem-sucedido';

    /**
     * @var string Mensagem de sucesso padrão para logout
     */
    public $logoutSuccessMessage = 'Logout realizado com sucesso';

    /**
     * @var string Role requerida para autenticação via API
     */
    public $requiredRole = 'cliente';

    /**
     * @var bool Se deve invalidar o token atual no logout
     */
    public $invalidateTokenOnLogout = true;

    /**
     * Inicialização do componente
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Realiza login de um usuário
     *
     * @param string $username Nome de usuário ou email
     * @param string $password Senha
     * @return array Dados do usuário autenticado
     * @throws UnauthorizedHttpException Se as credenciais forem inválidas
     * @throws ForbiddenHttpException Se o usuário não tiver permissão
     */
    public function login($username, $password)
    {
        // Validar parâmetros
        if (empty($username) || empty($password)) {
            throw new UnauthorizedHttpException('Credenciais inválidas');
        }

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
        if ($this->requiredRole) {
            $auth = Yii::$app->authManager;
            if ($auth) {
                // RBAC está configurado, verificar role
                if (!$auth->checkAccess($user->id, $this->requiredRole)) {
                    Yii::warning("Login sem a role '{$this->requiredRole}' para o usuário: {$username}", __METHOD__);
                    throw new ForbiddenHttpException(
                        'Acesso negado. Apenas clientes podem usar a aplicação mobile.'
                    );
                }
            } else {
                // RBAC não está configurado, apenas logar aviso
                Yii::warning("RBAC não configurado - ignorando verificação de role para o usuário: {$username}", __METHOD__);
            }
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
            'message' => $this->loginSuccessMessage,
            'token' => $user->auth_key,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
        ];
    }

    /**
     * Realiza logout de um usuário
     *
     * @param string $token Token de acesso (auth_key)
     * @return array Resultado do logout
     */
    public function logout($token)
    {
        if (empty($token)) {
            return [
                'success' => false,
                'message' => 'Token não fornecido'
            ];
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

        // Se configurado para invalidar token, gerar novo
        if ($this->invalidateTokenOnLogout) {
            $user->generateAuthKey();
            if ($user->save(false)) {
                Yii::info("Token invalidated for user ID: {$user->id}", __METHOD__);
            } else {
                Yii::error("Failed to invalidate token for user ID: {$user->id}", __METHOD__);
            }
        }

        return [
            'success' => true,
            'message' => $this->logoutSuccessMessage
        ];
    }

    /**
     * Valida um token de acesso
     *
     * @param string $token Token de acesso
     * @return User|null Usuário autenticado ou null
     */
    public function validateToken($token)
    {
        if (empty($token)) {
            return null;
        }

        return User::findIdentityByAccessToken($token);
    }

    /**
     * Verifica se um token é válido
     *
     * @param string $token Token de acesso
     * @return bool
     */
    public function isValidToken($token)
    {
        return $this->validateToken($token) !== null;
    }

    /**
     * Inicia processo de recuperação de senha
     *
     * @param string $email Email do usuário
     * @return array Resultado da operação
     */
    public function requestPasswordReset($email)
    {
        if (empty($email)) {
            return [
                'success' => false,
                'message' => 'Email é obrigatório'
            ];
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Email inválido'
            ];
        }

        // Buscar usuário ativo pelo email
        $user = User::findOne([
            'email' => $email,
            'status' => User::STATUS_ACTIVE,
        ]);


        //Verificar se user com esse email existe
        if (!$user) {
            Yii::warning("Password reset requested for non-existent email: {$email}", __METHOD__);
            // Por segurança, não revelar que o email não existe
            return [
                'success' => false,
                'message' => 'Este email não está registado no sistema.'
            ];
        }
        
        // Gerar token de recuperação
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                Yii::error("Failed to save password reset token for: {$email}", __METHOD__);
                return [
                    'success' => false,
                    'message' => 'Erro ao processar solicitação'
                ];
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
     * Obtém informações do usuário autenticado pelo token
     *
     * @param string $token Token de acesso
     * @return array|null Dados do usuário ou null
     */
    public function getUserInfo($token)
    {
        $user = $this->validateToken($token);

        if (!$user || !$user->userprofile) {
            return null;
        }

        $userprofile = $user->userprofile;

        return [
            'id' => $userprofile->id,
            'nomecompleto' => $userprofile->nomecompleto,
            'username' => $user->username,
            'email' => $user->email,
            'telemovel' => $userprofile->telemovel,
            'nif' => $userprofile->nif,
            'tipo' => 'cliente'
        ];
    }
}


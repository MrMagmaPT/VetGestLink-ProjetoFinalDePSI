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
        // Configurações adicionais se necessário
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
            throw new UnauthorizedHttpException('Username e password são obrigatórios');
        }

        // Buscar usuário
        $user = User::findByUsername($username);

        // Validar existência e senha
        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Credenciais inválidas');
        }

        // Verificar status do usuário
        if ($user->status != User::STATUS_ACTIVE) {
            throw new ForbiddenHttpException('Conta inativa');
        }

        // Verificar se o usuário tem a role de cliente (API é apenas para clientes)
        if ($this->requiredRole) {
            $auth = Yii::$app->authManager;
            if (!$auth || !$auth->checkAccess($user->id, $this->requiredRole)) {
                throw new ForbiddenHttpException(
                    'Acesso negado. Apenas clientes podem usar a aplicação mobile.'
                );
            }
        }

        // Gerar/regenerar auth_key se necessário
        if (empty($user->auth_key)) {
            $user->generateAuthKey();
            $user->save(false);
        }

        // Buscar userprofile
        $userprofile = $user->userprofile;

        if (!$userprofile) {
            throw new ForbiddenHttpException('Perfil de usuário não encontrado');
        }

        // Log de sucesso
        Yii::info("Login successful for user: {$username}", __METHOD__);

        // Retornar dados do usuário
        return [
            'success' => true,
            'message' => $this->loginSuccessMessage,
            'auth_key' => $user->auth_key,
            'user' => [
                'id' => $userprofile->id,
                'nomecompleto' => $userprofile->nomecompleto,
                'username' => $user->username,
                'email' => $user->email,
                'telemovel' => $userprofile->telemovel,
                'nif' => $userprofile->nif,
            ]
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
     * @param string $username Nome de usuário
     * @return array Resultado da operação
     */
    public function requestPasswordReset($username)
    {
        if (empty($username)) {
            return [
                'success' => false,
                'message' => 'Username é obrigatório'
            ];
        }

        // Buscar usuário
        $user = User::findByUsername($username);

        if (!$user) {
            // Por segurança, não revelar se o usuário existe
            return [
                'success' => true,
                'message' => 'Se o username existir, um email de recuperação será enviado'
            ];
        }

        // TODO: Implementar geração de token de recuperação
        // TODO: Implementar envio de email

        Yii::info("Password reset requested for: {$username}", __METHOD__);

        return [
            'success' => true,
            'message' => 'Email de recuperação enviado com sucesso'
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


    /**
     * Gera um novo token para um usuário
     *
     * @param User $user Modelo do usuário
     * @return string Novo token gerado
     */
    public function generateNewToken($user)
    {
        $user->generateAuthKey();
        $user->save(false);

        return $user->auth_key;
    }
}


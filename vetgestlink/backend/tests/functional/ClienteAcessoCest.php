<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\tests\functional\_support\RbacHelper;
use common\models\User;
use Yii;

/**
 * Testes funcionais de restrição de acesso de Cliente ao Backend.
 * 
 * Esta suite de testes verifica que usuários com papel de Cliente
 * NÃO conseguem acessar o backend do sistema, garantindo que apenas
 * usuários com permissão 'backendAccess' possam entrar.
 */
class ClienteAcessoCest
{
    use RbacHelper;
    
    private $clienteUser;

    public function _before(FunctionalTester $I)
    {
        // Inicializa RBAC
        $this->initializeRbac();
        
        // Cria usuário cliente diretamente
        $timestamp = time();
        $this->clienteUser = new User();
        $this->clienteUser->username = 'cliente_test_' . $timestamp;
        $this->clienteUser->email = 'cliente_test_' . $timestamp . '@test.com';
        $this->clienteUser->setPassword('password123');
        $this->clienteUser->generateAuthKey();
        $this->clienteUser->status = User::STATUS_ACTIVE;
        
        if (!$this->clienteUser->save(false)) {
            throw new \Exception('Failed to save cliente user');
        }

        // Atribui papel de cliente
        $auth = Yii::$app->authManager;
        $clienteRole = $auth->getRole('cliente');
        if ($clienteRole && $this->clienteUser->id) {
            $auth->assign($clienteRole, $this->clienteUser->id);
        }
    }

    public function _after(FunctionalTester $I)
    {
        // Limpa dados criados
        if ($this->clienteUser) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($this->clienteUser->id);
            $this->clienteUser->delete();
        }
    }

    /**
     * Testa se o cliente é bloqueado ao tentar fazer login no backend.
     * 
     * Cenário: Cliente tenta acessar a página de login do backend e submete credenciais.
     * Expectativa: O login deve ser bloqueado ou, se permitido, não deve ter acesso
     * ao dashboard devido à falta da permissão 'backendAccess'.
     * Razão: Clientes são usuários do frontend e não devem acessar áreas administrativas.
     */
    public function testClienteNaoDeveAcessarBackend(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => $this->clienteUser->username,
            'LoginForm[password]' => 'password123',
        ]);
        
        // Tenta acessar o dashboard do backend
        $I->amOnRoute('/site/index');
        
        // Cliente NÃO deve ter acesso ao backend
        // Deve ver mensagem de acesso negado ou ser redirecionado
        $I->see('Acesso Negado');
        $I->dontSee('Dashboard');
    }

    /**
     * Testa se o cliente é bloqueado ao tentar acessar páginas administrativas.
     * 
     * Cenário: Cliente tenta acessar diretamente uma página administrativa (animais).
     * Expectativa: Deve ver mensagem de acesso negado.
     */
    public function testClienteNaoDeveAcessarPaginasAdministrativas(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => $this->clienteUser->username,
            'LoginForm[password]' => 'password123',
        ]);
        
        // Tenta acessar página de animais
        $I->amOnRoute('/animal/index');
        
        // Deve ser bloqueado
        $I->see('Acesso Negado');
        $I->dontSee('Animais');
    }
}

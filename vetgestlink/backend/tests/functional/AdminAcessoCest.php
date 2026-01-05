<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\tests\functional\_support\RbacHelper;
use common\models\User;
use Yii;

/**
 * Testes funcionais de Acesso do Administrador.
 * 
 * Esta suite de testes verifica o processo de autenticação e acesso
 * do administrador ao backend, incluindo validação de permissões.
 */
class AdminAcessoCest
{
    use RbacHelper;
    
    private $adminUser;

    public function _before(FunctionalTester $I)
    {
        // Inicializa RBAC
        $this->initializeRbac();
        
        // Cria usuário administrador diretamente
        $timestamp = time();
        $this->adminUser = new User();
        $this->adminUser->username = 'admin_test_' . $timestamp;
        $this->adminUser->email = 'admin_test_' . $timestamp . '@test.com';
        $this->adminUser->setPassword('password123');
        $this->adminUser->generateAuthKey();
        $this->adminUser->status = User::STATUS_ACTIVE;
        
        if (!$this->adminUser->save(false)) {
            throw new \Exception('Failed to save admin user');
        }

        // Atribui papel de admin
        $auth = Yii::$app->authManager;
        $adminRole = $auth->getRole('admin');
        if ($adminRole && $this->adminUser->id) {
            $auth->assign($adminRole, $this->adminUser->id);
        }
    }

    public function _after(FunctionalTester $I)
    {
        // Limpa dados criados
        if ($this->adminUser) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($this->adminUser->id);
            $this->adminUser->delete();
        }
    }
    
    /**
     * Testa o processo completo de login do administrador no backend.
     * 
     * Cenário: Administrador acessa a página de login e submete suas credenciais.
     * Expectativa: 
     * - O login deve ser bem-sucedido
     * - Deve ver seu nome de usuário na página
     * - Deve ter acesso ao dashboard do backend
     * - Não deve ver mensagens de acesso negado
     * - A permissão 'backendAccess' deve estar ativa
     * 
     * @param FunctionalTester $I
     */
    public function testAdministradorDeveFazerLoginComSucessoEAcessarBackend(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => $this->adminUser->username,
            'LoginForm[password]' => 'password123',
        ]);
        
        // Verifica que o login foi bem-sucedido
        $I->see($this->adminUser->username);
        $I->dontSee('Login');
        
        // Verifica explicitamente a permissão backendAccess
        // Tenta acessar o dashboard do backend
        $I->amOnRoute('/site/index');
        
        // Confirma que não há mensagens de acesso negado
        $I->dontSee('Acesso Negado');
        $I->dontSee('Forbidden');
        $I->dontSee('Entre para aceder');
        
        // Verifica que elementos do dashboard estão visíveis (só possível com backendAccess)
        $I->see('Dashboard');
        $I->see($this->adminUser->username);
    }
}

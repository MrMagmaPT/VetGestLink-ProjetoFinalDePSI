<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\tests\functional\_support\RbacHelper;
use common\models\User;
use Yii;

/**
 * Testes funcionais de restrição de acesso do Veterinário às Faturas.
 * 
 * Esta suite de testes verifica que o papel de Veterinário NÃO tem
 * permissão para acessar funcionalidades relacionadas a faturas,
 * garantindo a segregação de responsabilidades no sistema.
 */
class VeterinarioFaturaCest
{
    use RbacHelper;
    
    private $veterinarioUser;

    public function _before(FunctionalTester $I)
    {
        // Inicializa RBAC
        $this->initializeRbac();
        
        // Cria usuário veterinário diretamente
        $timestamp = time();
        $this->veterinarioUser = new User();
        $this->veterinarioUser->username = 'veterinario_fatura_' . $timestamp;
        $this->veterinarioUser->email = 'veterinario_fatura_' . $timestamp . '@test.com';
        $this->veterinarioUser->setPassword('password123');
        $this->veterinarioUser->generateAuthKey();
        $this->veterinarioUser->status = User::STATUS_ACTIVE;
        
        if (!$this->veterinarioUser->save(false)) {
            throw new \Exception('Failed to save veterinario user');
        }

        // Atribui papel de veterinário
        $auth = Yii::$app->authManager;
        $veterinarioRole = $auth->getRole('veterinario');
        if ($veterinarioRole && $this->veterinarioUser->id) {
            $auth->assign($veterinarioRole, $this->veterinarioUser->id);
        }

        // Faz login
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => $this->veterinarioUser->username,
            'LoginForm[password]' => 'password123',
        ]);
    }

    public function _after(FunctionalTester $I)
    {
        // Limpa dados criados
        if ($this->veterinarioUser) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($this->veterinarioUser->id);
            $this->veterinarioUser->delete();
        }
    }

    /**
     * Testa se o veterinário consegue acessar o dashboard.
     * 
     * Cenário: Veterinário faz login e acessa a página inicial.
     * Expectativa: Deve ver seu nome de usuário sem solicitação de novo login.
     */
    public function testVeterinarioDeveAcessarDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see($this->veterinarioUser->username);
        $I->dontSee('Entre para aceder');
    }

    /**
     * Testa se o veterinário É BLOQUEADO ao tentar acessar faturas.
     * 
     * Cenário: Veterinário tenta acessar a página de faturas.
     * Expectativa: Deve ver mensagem "Acesso Negado" e NÃO ver a lista de faturas.
     * Razão: Veterinários não devem ter acesso a informações financeiras.
     */
    public function testVeterinarioNaoDeveAcessarFaturas(FunctionalTester $I)
    {
        // Veterinário NÃO tem permissão para acessar faturas
        $I->amOnRoute('/fatura/index');
        $I->see('Acesso Negado');
        $I->dontSee('Lista de Faturas');
    }
}

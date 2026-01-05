<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\tests\functional\_support\RbacHelper;
use common\models\User;
use Yii;

/**
 * Testes funcionais de acesso do Veterinário às Marcações.
 * 
 * Esta suite de testes verifica as permissões de acesso do papel
 * de Veterinário às funcionalidades de gestão de marcações no sistema.
 */
class VeterinarioMarcacaoCest
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
        $this->veterinarioUser->username = 'veterinario_marcacao_' . $timestamp;
        $this->veterinarioUser->email = 'veterinario_marcacao_' . $timestamp . '@test.com';
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
     * Cenário: Veterinário faz login e acessa a página inicial do backend.
     * Expectativa: Deve ver o dashboard e seu nome de usuário sem restrições.
     */
    public function testVeterinarioDeveAcessarDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('Dashboard');
        $I->see($this->veterinarioUser->username);
        $I->dontSee('Acesso Negado');
    }

    /**
     * Testa se o veterinário tem acesso à listagem de marcações.
     * 
     * Cenário: Veterinário navega para a página de índice de marcações.
     * Expectativa: Deve ver a página "Marcações" sem mensagens de acesso negado.
     */
    public function testVeterinarioDeveAcessarListagemDeMarcacoes(FunctionalTester $I)
    {
        $I->amOnRoute('/marcacao/index');
        $I->see('Marcações');
        $I->dontSee('Acesso Negado');
    }
}

<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\tests\functional\_support\RbacHelper;
use common\models\User;
use Yii;

/**
 * Testes funcionais de Acesso do Recepcionista.
 * 
 * Esta suite de testes verifica o acesso e permissões do papel
 * de Recepcionista às funcionalidades do backend, incluindo
 * acesso ao dashboard e listagem de animais.
 */
class RecepAcessoCest
{
    use RbacHelper;
    
    private $recepcionistaUser;

    public function _before(FunctionalTester $I)
    {
        // Inicializa RBAC
        $this->initializeRbac();
        
        // Cria usuário recepcionista diretamente
        $timestamp = time();
        $this->recepcionistaUser = new User();
        $this->recepcionistaUser->username = 'rececionista_test_' . $timestamp;
        $this->recepcionistaUser->email = 'rececionista_test_' . $timestamp . '@test.com';
        $this->recepcionistaUser->setPassword('password123');
        $this->recepcionistaUser->generateAuthKey();
        $this->recepcionistaUser->status = User::STATUS_ACTIVE;
        
        if (!$this->recepcionistaUser->save(false)) {
            throw new \Exception('Failed to save recepcionista user');
        }

        // Atribui papel de recepcionista
        $auth = Yii::$app->authManager;
        $recepcionistaRole = $auth->getRole('rececionista');
        if ($recepcionistaRole && $this->recepcionistaUser->id) {
            $auth->assign($recepcionistaRole, $this->recepcionistaUser->id);
        }

        // Faz login
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => $this->recepcionistaUser->username,
            'LoginForm[password]' => 'password123',
        ]);
    }

    public function _after(FunctionalTester $I)
    {
        // Limpa dados criados
        if ($this->recepcionistaUser) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($this->recepcionistaUser->id);
            $this->recepcionistaUser->delete();
        }
    }

    /**
     * Testa se o recepcionista consegue acessar o dashboard após o login.
     * 
     * Cenário: Recepcionista faz login e tenta acessar a página inicial do backend.
     * Expectativa: Deve ver o dashboard e seu nome de usuário, sem mensagens de erro.
     */
    public function testRececionistaDeveAcessarDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('Dashboard');
        $I->see($this->recepcionistaUser->username);
        $I->dontSee('Login');
    }

    /**
     * Testa se o recepcionista tem acesso à página de listagem de animais.
     * 
     * Cenário: Recepcionista navega para a página de índice de animais.
     * Expectativa: Deve ver a página "Animais" sem mensagens de acesso negado.
     */
    public function testRececionistaDeveAcessarListagemDeAnimais(FunctionalTester $I)
    {
        $I->amOnRoute('/animal/index');
        $I->see('Animais');
        $I->dontSee('Acesso Negado');
    }
}

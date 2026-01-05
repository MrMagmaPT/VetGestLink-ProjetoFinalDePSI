<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\tests\functional\_support\RbacHelper;
use common\models\User;
use Yii;

/**
 * Testes funcionais de Acesso às Marcações.
 * 
 * Esta suite de testes verifica o acesso e permissões relacionadas
 * à gestão de marcações no sistema, incluindo permissões de acesso
 * para o papel de Recepcionista.
 */
class MarcacaoAcessoCest
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
        $this->recepcionistaUser->username = 'rececionista_marcacao_' . $timestamp;
        $this->recepcionistaUser->email = 'rececionista_marcacao_' . $timestamp . '@test.com';
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
     * Testa se o recepcionista consegue acessar o dashboard.
     * 
     * Cenário: Recepcionista faz login e acessa a página inicial do backend.
     * Expectativa: Deve ver o dashboard e seu nome de usuário sem restrições.
     */
    public function testRecepcionistaDeveAcessarDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('Dashboard');
        $I->see($this->recepcionistaUser->username);
        $I->dontSee('Acesso Negado');
    }

    /**
     * Testa se o recepcionista tem acesso à página de marcações.
     * 
     * Cenário: Recepcionista navega para a página de índice de marcações.
     * Expectativa: Deve ver a página "Marcações" sem mensagens de acesso negado.
     */
    public function testRecepcionistaDeveAcessarListagemDeMarcacoes(FunctionalTester $I)
    {
        $I->amOnRoute('/marcacao/index');
        $I->see('Marcações');
        $I->dontSee('Acesso Negado');
    }
}

<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\AuthAssignmentFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
            ],
            'auth_assignment' => [
                'class' => AuthAssignmentFixture::class,
            ],
        ];
    }
    
    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'password_0',
        ]);
        
        // Verifica que o login foi bem-sucedido
        $I->see('admin');
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
        $I->see('admin');
    }
}

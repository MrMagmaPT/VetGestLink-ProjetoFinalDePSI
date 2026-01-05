<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\AuthAssignmentFixture;

class AnimalCrudCest
{
    public function _fixtures()
    {
        return [
            'user' => UserFixture::class,
            'auth' => AuthAssignmentFixture::class,
        ];
    }

    public function _before(FunctionalTester $I)
    {
        // Recepcionista tem permissões para ver animais
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'rececionista',
            'LoginForm[password]' => 'password_0',
        ]);
    }

    public function testAccessDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('Dashboard');
        $I->see('rececionista');
        $I->dontSee('Login');
    }

    public function testAccessAnimalIndex(FunctionalTester $I)
    {
        $I->amOnRoute('/animal/index');
        $I->see('Animais');
        $I->dontSee('Acesso Negado');
    }
}

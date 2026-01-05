<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\AuthAssignmentFixture;

class FaturaCrudCest
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
        // Recepcionista tem permissões para ver faturas
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'rececionista',
            'LoginForm[password]' => 'password_0',
        ]);
    }

    public function testAccessDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('rececionista');
        $I->dontSee('Entre para aceder');
    }

    public function testAccessFaturaIndex(FunctionalTester $I)
    {
        $I->amOnRoute('/fatura/index');
        $I->see('Faturas');
        $I->dontSee('Acesso Negado');
    }
}

<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\AuthAssignmentFixture;

class MarcacaoCrudCest
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
        // Recepcionista tem permissões para gerir marcações
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'rececionista',
            'LoginForm[password]' => 'password_0',
        ]);
    }

    public function testRececionistaCanAccessDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('Dashboard');
        $I->see('rececionista');
        $I->dontSee('Acesso Negado');
    }

    public function testRececionistaCanAccessMarcacoes(FunctionalTester $I)
    {
        $I->amOnRoute('/marcacao/index');
        $I->see('Marcações');
        $I->dontSee('Acesso Negado');
    }
}

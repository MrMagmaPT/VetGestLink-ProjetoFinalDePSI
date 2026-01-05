<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\AuthAssignmentFixture;

class VeterinarioFaturaCest
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
        // Veterinário tem permissões para ver faturas
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'veterinario',
            'LoginForm[password]' => 'password_0',
        ]);
    }

    public function testVeterinarioCanAccessDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('veterinario');
        $I->dontSee('Entre para aceder');
    }

    public function testVeterinarioCannotAccessFaturas(FunctionalTester $I)
    {
        // Veterinário NÃO tem permissão para acessar faturas
        $I->amOnRoute('/fatura/index');
        $I->see('Acesso Negado');
        $I->dontSee('Lista de Faturas');
    }
}

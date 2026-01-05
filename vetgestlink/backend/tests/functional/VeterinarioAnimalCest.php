<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\AuthAssignmentFixture;

class VeterinarioAnimalCest
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
        // Veterinário tem permissões para ver animais
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'veterinario',
            'LoginForm[password]' => 'password_0',
        ]);
    }

    public function testVeterinarioCanAccessDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->see('Dashboard');
        $I->see('veterinario');
        $I->dontSee('Login');
    }

    public function testVeterinarioCanAccessAnimals(FunctionalTester $I)
    {
        $I->amOnRoute('/animal/index');
        $I->see('Animais');
        $I->dontSee('Acesso Negado');
    }
}

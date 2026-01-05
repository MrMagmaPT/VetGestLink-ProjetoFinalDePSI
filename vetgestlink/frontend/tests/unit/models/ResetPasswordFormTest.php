<?php

namespace frontend\tests\unit\models;

use common\fixtures\UserFixture;
use frontend\models\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    /**
     * Testa que tokens inválidos são rejeitados na redefinição de senha
     * 
     * Verifica se o sistema lança exceção quando:
     * - O token está vazio
     * - O token não existe na base de dados
     */
    public function testDeveRejeitarTokenInvalido()
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function() {
            new ResetPasswordForm('');
        });

        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function() {
            new ResetPasswordForm('notexistingtoken_1391882543');
        });
    }

    /**
     * Testa a redefinição bem-sucedida de senha com token válido
     * 
     * Verifica se o sistema permite redefinir a senha quando
     * um token válido de recuperação é fornecido
     */
    public function testDeveRedefinirSenhaComTokenValido()
    {
        $user = $this->tester->grabFixture('user', 0);
        $form = new ResetPasswordForm($user['password_reset_token']);
        verify($form->resetPassword())->notEmpty();
    }

}

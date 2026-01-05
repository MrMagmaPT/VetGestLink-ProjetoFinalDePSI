<?php

namespace frontend\tests\unit\models;

use common\fixtures\UserFixture;
use frontend\models\VerifyEmailForm;

class VerifyEmailFormTest extends \Codeception\Test\Unit
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
            ]
        ]);
    }

    /**
     * Testa que tokens inválidos são rejeitados na verificação de email
     * 
     * Verifica se o sistema lança exceção quando:
     * - O token está vazio
     * - O token não existe na base de dados
     */
    public function testDeveRejeitarTokenInvalidoNaVerificacao()
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function() {
            new VerifyEmailForm('');
        });

        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function() {
            new VerifyEmailForm('notexistingtoken_1391882543');
        });
    }

    /**
     * Testa que tokens já utilizados são rejeitados
     * 
     * Verifica se o sistema impede a reutilização de tokens de verificação
     * que já foram usados para ativar uma conta
     */
    public function testDeveRejeitarTokenJaUtilizado()
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function() {
            new VerifyEmailForm('already_used_token_1548675330');
        });
    }

    /**
     * Testa a verificação bem-sucedida de email com token válido
     * 
     * Verifica se:
     * - O token válido ativa a conta do usuário
     * - O status do usuário é alterado para ativo
     * - Os dados do usuário estão corretos após verificação
     */
    public function testDeveVerificarEmailComTokenValido()
    {
        $model = new VerifyEmailForm('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
        $user = $model->verifyEmail();
        verify($user)->instanceOf('common\models\User');

        verify($user->username)->equals('test.test');
        verify($user->email)->equals('test@mail.com');
        verify($user->status)->equals(\common\models\User::STATUS_ACTIVE);
        verify($user->validatePassword('Test1234'))->true();
    }
}

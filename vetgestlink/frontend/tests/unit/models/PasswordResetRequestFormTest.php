<?php

namespace frontend\tests\unit\models;

use Yii;
use frontend\models\PasswordResetRequestForm;
use common\fixtures\UserFixture as UserFixture;
use common\models\User;

class PasswordResetRequestFormTest extends \Codeception\Test\Unit
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
     * Testa que o envio de email de recuperação de senha falha com email inexistente
     * 
     * Verifica se o sistema não envia email de recuperação quando o email
     * não está cadastrado na base de dados
     */
    public function testNaoDeveEnviarEmailParaEnderecoInexistente()
    {
        $model = new PasswordResetRequestForm();
        $model->email = 'not-existing-email@example.com';
        verify($model->sendEmail())->false();
    }

    /**
     * Testa que o sistema não envia email de recuperação para usuários inativos
     * 
     * Verifica se usuários com status inativo não podem receber emails
     * de recuperação de senha, evitando recuperação de contas desativadas
     */
    public function testNaoDeveEnviarEmailParaUsuarioInativo()
    {
        $user = $this->tester->grabFixture('user', 1);
        $model = new PasswordResetRequestForm();
        $model->email = $user['email'];
        verify($model->sendEmail())->false();
    }

    /**
     * Testa o envio bem-sucedido de email de recuperação de senha
     * 
     * Verifica se:
     * - O email é enviado para usuário ativo válido
     * - O token de recuperação de senha é gerado
     * - O email contém os destinatários e remetentes corretos
     */
    public function testDeveEnviarEmailDeRecuperacaoComSucesso()
    {
        $userFixture = $this->tester->grabFixture('user', 0);
        
        $model = new PasswordResetRequestForm();
        $model->email = $userFixture['email'];
        $user = User::findOne(['password_reset_token' => $userFixture['password_reset_token']]);

        verify($model->sendEmail())->notEmpty();
        verify($user->password_reset_token)->notEmpty();

        $emailMessage = $this->tester->grabLastSentEmail();
        verify($emailMessage)->instanceOf('yii\mail\MessageInterface');
        verify($emailMessage->getTo())->arrayHasKey($model->email);
        verify($emailMessage->getFrom())->arrayHasKey(Yii::$app->params['supportEmail']);
    }
}

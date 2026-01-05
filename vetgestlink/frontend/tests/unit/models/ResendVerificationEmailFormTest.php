<?php

namespace frontend\tests\unit\models;


use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use frontend\models\ResendVerificationEmailForm;

class ResendVerificationEmailFormTest extends Unit
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
     * Testa a validação de email inexistente no reenvio de verificação
     * 
     * Verifica se o sistema retorna erro apropriado quando tentamos
     * reenviar email de verificação para um endereço não cadastrado
     */
    public function testDeveRejeitarEmailInexistente()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'aaa@bbb.cc'
        ];

        verify($model->validate())->false();
        verify($model->hasErrors())->true();
        verify($model->getFirstError('email'))->equals('Não existe nenhum utilizador com este email ou já está ativo.');
    }

    /**
     * Testa a validação de campo email vazio
     * 
     * Verifica se o sistema exige que o campo email seja preenchido
     * ao tentar reenviar email de verificação
     */
    public function testDeveRejeitarEmailVazio()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => ''
        ];

        verify($model->validate())->false();
        verify($model->hasErrors())->true();
        verify($model->getFirstError('email'))->equals('Por favor, insira o seu email.');
    }

    /**
     * Testa que não é possível reenviar verificação para usuário já ativo
     * 
     * Verifica se o sistema impede o reenvio de email de verificação
     * para usuários que já tiveram suas contas ativadas
     */
    public function testNaoDeveReenviarParaUsuarioJaAtivo()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'test2@mail.com'
        ];

        verify($model->validate())->false();
        verify($model->hasErrors())->true();
        verify($model->getFirstError('email'))->equals('Não existe nenhum utilizador com este email ou já está ativo.');
    }

    /**
     * Testa o reenvio bem-sucedido de email de verificação
     * 
     * Verifica se:
     * - O formulário valida corretamente para usuário pendente
     * - O email é enviado com sucesso
     * - O email contém destinatários, remetente e token corretos
     */
    public function testDeveReenviarEmailDeVerificacaoComSucesso()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'test@mail.com'
        ];

        verify($model->validate())->true();
        verify($model->hasErrors())->false();

        verify($model->sendEmail())->true();
        $this->tester->seeEmailIsSent();

        $mail = $this->tester->grabLastSentEmail();

        verify($mail)->instanceOf('yii\mail\MessageInterface');
        verify($mail->getTo())->arrayHasKey('test@mail.com');
        verify($mail->getFrom())->arrayHasKey(\Yii::$app->params['supportEmail']);
        verify($mail->getSubject())->equals('Account registration at ' . \Yii::$app->name);
        verify($mail->toString())->stringContainsString('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
    }
}

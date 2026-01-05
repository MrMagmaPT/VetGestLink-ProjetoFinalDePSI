<?php

namespace common\tests\unit\models;

use Yii;
use common\models\LoginForm;
use common\fixtures\UserFixture;

/**
 * Testes do formulário de Login.
 * 
 * Esta suite de testes verifica o comportamento do LoginForm,
 * incluindo validação de credenciais, autenticação de usuários,
 * e tratamento de erros em casos de senha incorreta ou campos vazios.
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    /**
     * Testa se o login falha quando a senha está incorreta.
     * 
     * Cenário: Tentativa de login com username correto mas senha errada.
     * Expectativa: O método login() deve retornar false e deve haver erro no campo 'password'.
     */
    public function testDeveRejeitarLoginComSenhaIncorreta()
    {
        $model = new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'wrong_password',
        ]);

        verify($model->login())->false();
        verify($model->errors)->arrayHasKey('password');
    }

    /**
     * Testa se a validação falha quando os campos estão vazios.
     * 
     * Cenário: Formulário de login submetido sem preencher username e password.
     * Expectativa: A validação deve falhar e mostrar erros para ambos os campos.
     */
    public function testDeveValidarCamposObrigatoriosDoLogin()
    {
        $model = new LoginForm([
            'username' => '',
            'password' => '',
        ]);

        verify($model->validate())->false();
        verify($model->errors)->arrayHasKey('username');
        verify($model->errors)->arrayHasKey('password');
    }
}

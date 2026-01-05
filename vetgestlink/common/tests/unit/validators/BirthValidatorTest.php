<?php

namespace common\tests\unit\validators;

use common\validators\BirthValidator;
use yii\base\DynamicModel;

/**
 * Testes do validador de data de nascimento.
 * 
 * Este conjunto de testes verifica o comportamento do BirthValidator,
 * que é responsável por validar datas de nascimento de acordo com regras de negócio,
 * como verificar se a data não é futura e se atende requisitos de idade mínima.
 */
class BirthValidatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /**
     * Testa se o validador aceita uma data de nascimento válida.
     * 
     * Cenário: Uma pessoa com 20 anos (maior de idade).
     * Expectativa: A validação deve passar sem erros.
     */
    public function testDeveAceitarDataDeNascimentoValidaDeMaiorDeIdade()
    {
        $dataValida = date('Y-m-d', strtotime('-20 years'));
        
        $model = DynamicModel::validateData(
            ['dtanascimento' => $dataValida],
            [['dtanascimento', BirthValidator::class]]
        );

        $this->assertFalse($model->hasErrors('dtanascimento'));
    }

    /**
     * Testa se o validador rejeita uma data de nascimento futura.
     * 
     * Cenário: Data de nascimento é amanhã (data futura).
     * Expectativa: A validação deve falhar com erro, pois ninguém pode nascer no futuro.
     */
    public function testDeveRejeitarDataDeNascimentoFutura()
    {
        $dataFutura = date('Y-m-d', strtotime('+1 day'));
        
        $model = DynamicModel::validateData(
            ['dtanascimento' => $dataFutura],
            [['dtanascimento', BirthValidator::class]]
        );

        $this->assertTrue($model->hasErrors('dtanascimento'));
    }
}

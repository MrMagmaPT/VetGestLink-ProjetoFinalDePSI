<?php

namespace common\tests\unit\validators;

use common\validators\NifValidator;
use yii\base\DynamicModel;

/**
 * Testes do validador de NIF (Número de Identificação Fiscal).
 * 
 * Este conjunto de testes verifica o comportamento do NifValidator,
 * que valida se um NIF português é válido de acordo com as regras:
 * - Deve ter exatamente 9 dígitos
 * - Deve conter apenas números
 */
class NifValidatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /**
     * Testa se o validador aceita um NIF válido com 9 dígitos.
     * 
     * Cenário: NIF com formato correto (9 dígitos numéricos).
     * Expectativa: A validação deve passar sem erros.
     */
    public function testDeveAceitarNifValidoCom9Digitos()
    {
        $model = DynamicModel::validateData(
            ['nif' => '123456789'],
            [['nif', NifValidator::class]]
        );

        $this->assertFalse($model->hasErrors('nif'));
    }

    /**
     * Testa se o validador rejeita um NIF com menos de 9 dígitos.
     * 
     * Cenário: NIF com apenas 8 dígitos.
     * Expectativa: A validação deve falhar pois o NIF tem tamanho incorreto.
     */
    public function testDeveRejeitarNifComMenosDe9Digitos()
    {
        $model = DynamicModel::validateData(
            ['nif' => '12345678'],
            [['nif', NifValidator::class]]
        );

        $this->assertTrue($model->hasErrors('nif'));
    }

    /**
     * Testa se o validador rejeita um NIF que contém letras.
     * 
     * Cenário: NIF com caracteres não numéricos (letra 'A').
     * Expectativa: A validação deve falhar pois o NIF deve conter apenas números.
     */
    public function testDeveRejeitarNifQueContemLetras()
    {
        $model = DynamicModel::validateData(
            ['nif' => '12345678A'],
            [['nif', NifValidator::class]]
        );

        $this->assertTrue($model->hasErrors('nif'));
    }
}

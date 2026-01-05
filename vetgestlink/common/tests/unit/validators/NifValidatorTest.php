<?php

namespace common\tests\unit\validators;

use common\validators\NifValidator;
use yii\base\DynamicModel;

class NifValidatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    // NIF válido com 9 dígitos
    public function testValidNif()
    {
        $model = DynamicModel::validateData(
            ['nif' => '123456789'],
            [['nif', NifValidator::class]]
        );

        $this->assertFalse($model->hasErrors('nif'));
    }

    // NIF inválido - menos de 9 dígitos
    public function testInvalidNifTooShort()
    {
        $model = DynamicModel::validateData(
            ['nif' => '12345678'],
            [['nif', NifValidator::class]]
        );

        $this->assertTrue($model->hasErrors('nif'));
    }

    // NIF inválido - contém letras
    public function testInvalidNifWithLetters()
    {
        $model = DynamicModel::validateData(
            ['nif' => '12345678A'],
            [['nif', NifValidator::class]]
        );

        $this->assertTrue($model->hasErrors('nif'));
    }
}

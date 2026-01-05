<?php

namespace common\tests\unit\validators;

use common\validators\BirthValidator;
use yii\base\DynamicModel;

class BirthValidatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    // Data de nascimento válida (maior de 18 anos)
    public function testValidBirthDate()
    {
        $dataValida = date('Y-m-d', strtotime('-20 years'));
        
        $model = DynamicModel::validateData(
            ['dtanascimento' => $dataValida],
            [['dtanascimento', BirthValidator::class]]
        );

        $this->assertFalse($model->hasErrors('dtanascimento'));
    }

    // Data de nascimento inválida (futura)
    public function testInvalidBirthDateFuture()
    {
        $dataFutura = date('Y-m-d', strtotime('+1 day'));
        
        $model = DynamicModel::validateData(
            ['dtanascimento' => $dataFutura],
            [['dtanascimento', BirthValidator::class]]
        );

        $this->assertTrue($model->hasErrors('dtanascimento'));
    }
}

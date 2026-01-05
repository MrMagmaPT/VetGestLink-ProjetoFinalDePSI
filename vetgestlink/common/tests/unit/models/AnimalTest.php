<?php

namespace common\tests\unit\models;

use common\models\Animal;
use common\fixtures\AnimalFixture;
use common\fixtures\EspecieFixture;
use common\fixtures\RacaFixture;
use common\fixtures\UserprofileFixture;

class AnimalTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'especies' => EspecieFixture::class,
            'racas' => RacaFixture::class,
            'userprofiles' => UserprofileFixture::class,
            'animais' => AnimalFixture::class,
        ];
    }

    // Validação de campos obrigatórios
    public function testValidationRequired()
    {
        $animal = new Animal();
        
        $this->assertFalse($animal->validate());
        $this->assertTrue($animal->hasErrors('nome'));
        $this->assertTrue($animal->hasErrors('especies_id'));
    }

    // Validação de atributos individuais
    public function testCreateValidAnimal()
    {
        $animal = new Animal([
            'nome' => 'Bobby',
            'dtanascimento' => '2022-05-10',
            'peso' => 15.5,
            'sexo' => 'M',
        ]);

        // Valida apenas os atributos sem FK
        $animal->validate(['nome', 'dtanascimento', 'peso', 'sexo']);
        $this->assertFalse($animal->hasErrors('nome'));
        $this->assertFalse($animal->hasErrors('dtanascimento'));
    }

    // Teste de sexo válido
    public function testValidSexo()
    {
        $animal = new Animal(['sexo' => 'M']);
        $animal->validate(['sexo']);
        $this->assertFalse($animal->hasErrors('sexo'));
        
        $animal2 = new Animal(['sexo' => 'F']);
        $animal2->validate(['sexo']);
        $this->assertFalse($animal2->hasErrors('sexo'));
    }
}

<?php

namespace common\tests\unit\models;

use common\models\Animal;
use common\fixtures\AnimalFixture;
use common\fixtures\EspecieFixture;
use common\fixtures\RacaFixture;
use common\fixtures\UserprofileFixture;

/**
 * Testes do modelo Animal.
 * 
 * Esta suite de testes verifica o comportamento do modelo Animal,
 * incluindo validações de campos obrigatórios, formatos de dados,
 * e regras de negócio relacionadas aos animais registrados no sistema.
 */
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

    /**
     * Testa se o modelo valida corretamente os campos obrigatórios.
     * 
     * Cenário: Tentativa de criar um animal sem preencher campos obrigatórios.
     * Expectativa: A validação deve falhar, exibindo erros para 'nome' e 'especies_id'.
     */
    public function testDeveValidarCamposObrigatorios()
    {
        $animal = new Animal();
        
        $this->assertFalse($animal->validate());
        $this->assertTrue($animal->hasErrors('nome'));
        $this->assertTrue($animal->hasErrors('especies_id'));
    }

    /**
     * Testa se é possível criar um animal com dados válidos.
     * 
     * Cenário: Criação de um animal com atributos válidos (nome, data nascimento, peso, sexo).
     * Expectativa: A validação dos campos fornecidos deve passar sem erros.
     */
    public function testDeveCriarAnimalComDadosValidos()
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

    /**
     * Testa se o modelo aceita valores válidos para o campo sexo.
     * 
     * Cenário: Teste com sexo masculino ('M') e feminino ('F').
     * Expectativa: Ambos os valores devem ser aceitos sem erros de validação.
     */
    public function testDeveAceitarSexosMasculinoEFeminino()
    {
        $animal = new Animal(['sexo' => 'M']);
        $animal->validate(['sexo']);
        $this->assertFalse($animal->hasErrors('sexo'));
        
        $animal2 = new Animal(['sexo' => 'F']);
        $animal2->validate(['sexo']);
        $this->assertFalse($animal2->hasErrors('sexo'));
    }
}

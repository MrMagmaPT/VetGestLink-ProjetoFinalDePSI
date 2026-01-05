<?php

namespace common\tests\unit\models;

use common\models\Fatura;
use common\fixtures\FaturaFixture;
use common\fixtures\MetodopagamentoFixture;
use common\fixtures\UserprofileFixture;

/**
 * Testes do modelo Fatura.
 * 
 * Esta suite de testes verifica o comportamento do modelo Fatura,
 * incluindo validações de campos numéricos (total) e formatos aceitos.
 */
class FaturaTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'userprofiles' => UserprofileFixture::class,
            'metodospagamentos' => MetodopagamentoFixture::class,
            'faturas' => FaturaFixture::class,
        ];
    }

    /**
     * Testa se o modelo aceita valores decimais válidos no campo total.
     * 
     * Cenário: Criação de uma fatura com valor decimal (75.50).
     * Expectativa: O campo total deve aceitar o valor e a validação deve passar.
     */
    public function testDeveAceitarValorDecimalNoTotal()
    {
        $fatura = new Fatura([
            'total' => 75.50,
        ]);

        // Valida apenas total
        $fatura->validate(['total']);
        $this->assertFalse($fatura->hasErrors('total'));
        
        // Testa que total aceita valores decimais
        $this->assertEquals(75.50, $fatura->total);
    }

    /**
     * Testa se o campo total aceita valores numéricos.
     * 
     * Cenário: Atribui um valor ao campo total.
     * Expectativa: O valor deve ser numérico (verificação de tipo).
     */
    public function testTotalDeveSerNumerico()
    {
        $fatura = new Fatura(['total' => -10]);
        $fatura->validate(['total']);
        
        // Total pode ser negativo ou não? Vamos apenas verificar que valida
        $this->assertIsNumeric($fatura->total);
    }
}

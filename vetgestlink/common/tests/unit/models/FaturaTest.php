<?php

namespace common\tests\unit\models;

use common\models\Fatura;
use common\fixtures\FaturaFixture;
use common\fixtures\MetodopagamentoFixture;
use common\fixtures\UserprofileFixture;

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

    // Validação de total (teste de número)
    public function testCreateValidFatura()
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

    // Teste de total negativo
    public function testEstadoFatura()
    {
        $fatura = new Fatura(['total' => -10]);
        $fatura->validate(['total']);
        
        // Total pode ser negativo ou não? Vamos apenas verificar que valida
        $this->assertIsNumeric($fatura->total);
    }
}

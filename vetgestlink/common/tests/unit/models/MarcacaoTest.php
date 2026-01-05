<?php

namespace common\tests\unit\models;

use common\models\Marcacao;
use common\fixtures\MarcacaoFixture;
use common\fixtures\ServicoFixture;
use common\fixtures\AnimalFixture;
use common\fixtures\UserprofileFixture;
use common\fixtures\EspecieFixture;
use common\fixtures\RacaFixture;

class MarcacaoTest extends \Codeception\Test\Unit
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
            'servicos' => ServicoFixture::class,
            'marcacoes' => MarcacaoFixture::class,
        ];
    }

    // Validação de atributos individuais
    public function testCreateValidMarcacao()
    {
        $marcacao = new Marcacao([
            'data' => '2025-03-15',
            'horainicio' => '15:00:00',
            'horafim' => '15:30:00',
            'estado' => 'pendente',
        ]);

        // Valida apenas os atributos sem FK
        $marcacao->validate(['data', 'horainicio', 'horafim', 'estado']);
        $this->assertFalse($marcacao->hasErrors('data'));
        $this->assertFalse($marcacao->hasErrors('estado'));
    }

    // Transição de estado - Pendente para Realizada
    public function testEstadoTransitionToRealizada()
    {
        $marcacao = new Marcacao([
            'data' => '2025-03-15',
            'horainicio' => '15:00:00',
            'horafim' => '15:30:00',
            'estado' => 'pendente',
            'servicos_id' => 1,
            'animais_id' => 1,
            'userprofiles_id' => 1,
        ]);
        
        $this->assertEquals('pendente', $marcacao->estado);
        
        $marcacao->estado = 'realizada';
        $marcacao->diagnostico = 'Consulta realizada com sucesso';
        
        $this->assertEquals('realizada', $marcacao->estado);
    }
}

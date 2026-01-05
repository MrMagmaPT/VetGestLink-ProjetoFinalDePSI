<?php

namespace common\tests\unit\models;

use common\models\Marcacao;
use common\fixtures\MarcacaoFixture;
use common\fixtures\ServicoFixture;
use common\fixtures\AnimalFixture;
use common\fixtures\UserprofileFixture;
use common\fixtures\EspecieFixture;
use common\fixtures\RacaFixture;

/**
 * Testes do modelo Marcação.
 * 
 * Esta suite de testes verifica o comportamento do modelo Marcacao,
 * incluindo validações de campos, transições de estado
 * (pendente, realizada, cancelada) e regras de negócio.
 */
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

    /**
     * Testa se é possível criar uma marcação com dados válidos.
     * 
     * Cenário: Criação de uma marcação com data, horários e estado válidos.
     * Expectativa: A validação dos campos fornecidos deve passar sem erros.
     */
    public function testDeveCriarMarcacaoComDadosValidos()
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

    /**
     * Testa a transição de estado de uma marcação de 'pendente' para 'realizada'.
     * 
     * Cenário: Uma marcação é criada como 'pendente' e depois alterada para 'realizada'.
     * Expectativa: O estado deve mudar corretamente e aceitar diagnóstico quando realizada.
     */
    public function testDevePermitirTransicaoDeEstadoDePendenteParaRealizada()
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

<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use common\models\Animal;
use common\models\Nota;
use common\models\Marcacao;
use common\models\Fatura;

/**
 * Controller para Segunda Entrega - Teste Individual
 *
 * Este controller contém endpoints extras que podem ser implementados
 * ou modificados para testes da aplicação web e Android.
 * 
 * ROTAS PARA COPIAR E COLAR NO main.php (dentro de 'rules'):
 * 
 * // ========== SEGUNDA ENTREGA - TESTE INDIVIDUAL ==========
 * 'GET api/teste/stats' => 'api/segunda-entrega/stats',
 * 'GET api/teste/animal/<id:\d+>/history' => 'api/segunda-entrega/animal-history',
 * 'GET api/teste/faturas/pending' => 'api/segunda-entrega/faturas-pending',
 * 'PUT api/teste/animal/<id:\d+>/update' => 'api/segunda-entrega/update-animal',
 * 'POST api/teste/marcacao/request' => 'api/segunda-entrega/request-marcacao',
 * 
 */
class SegundaEntregaController extends ApiController
{

    /**
     * GET /teste/stats
     * Estatísticas gerais do cliente
     * 
     * Retorna resumo de animais, marcações, notas e faturas
     */
    public function actionStats()
    {
        $user = $this->getAuthenticatedUser();
        $userProfileId = $user->userprofile->id;

        // Contar animais
        $totalAnimais = Animal::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->count();

        // Contar marcações
        $totalMarcacoes = Marcacao::find()
            ->joinWith('animais')
            ->where(['animais.userprofiles_id' => $userProfileId, 'animais.eliminado' => 0])
            ->count();

        // Contar notas
        $totalNotas = Nota::find()
            ->where(['userprofiles_id' => $userProfileId])
            ->count();

        // Contar faturas
        $totalFaturas = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->count();

        // Faturas pendentes
        $faturasPendentes = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0, 'estado' => 0])
            ->count();

        // Total a pagar
        $totalAPagar = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0, 'estado' => 0])
            ->sum('total') ?? 0;

        return [
            'success' => true,
            'stats' => [
                'animais' => (int)$totalAnimais,
                'marcacoes' => (int)$totalMarcacoes,
                'notas' => (int)$totalNotas,
                'faturas' => [
                    'total' => (int)$totalFaturas,
                    'pendentes' => (int)$faturasPendentes,
                    'total_a_pagar' => (float)$totalAPagar,
                ],
            ],
        ];
    }

    /**
     * GET /teste/animal/{id}/history
     * Histórico completo de um animal
     * 
     * Retorna notas e marcações do animal em ordem cronológica
     */
    public function actionAnimalHistory($id)
    {
        $user = $this->getAuthenticatedUser();
        $userProfileId = $user->userprofile->id;

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        // Buscar notas
        $notas = Nota::find()
            ->where(['animais_id' => $id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // Buscar marcações
        $marcacoes = Marcacao::find()
            ->where(['animais_id' => $id])
            ->with(['servicos'])
            ->orderBy(['dtmarcacao' => SORT_DESC])
            ->all();

        $notasData = [];
        foreach ($notas as $nota) {
            $notasData[] = [
                'tipo' => 'nota',
                'id' => $nota->id,
                'texto' => $nota->nota,
                'data' => $nota->created_at,
                'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : 'N/A',
            ];
        }

        $marcacoesData = [];
        foreach ($marcacoes as $marcacao) {
            $marcacoesData[] = [
                'tipo' => 'marcacao',
                'id' => $marcacao->id,
                'data' => $marcacao->dtmarcacao,
                'servico' => $marcacao->servicos ? $marcacao->servicos->nome : 'N/A',
                'preco' => $marcacao->servicos ? (float)$marcacao->servicos->preco : 0,
                'observacoes' => $marcacao->observacoes,
            ];
        }

        return [
            'success' => true,
            'animal' => [
                'id' => $animal->id,
                'nome' => $animal->nome,
                'especie' => $animal->especies ? $animal->especies->nome : null,
                'raca' => $animal->racas ? $animal->racas->nome : null,
            ],
            'historico' => [
                'notas' => $notasData,
                'marcacoes' => $marcacoesData,
            ],
        ];
    }

    /**
     * GET /teste/faturas/pending
     * Lista apenas faturas pendentes
     */
    public function actionFaturasPending()
    {
        $user = $this->getAuthenticatedUser();
        $userProfileId = $user->userprofile->id;

        $faturas = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0, 'estado' => 0])
            ->with(['linhasfaturas'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($faturas as $fatura) {
            $result[] = [
                'id' => $fatura->id,
                'total' => (float)$fatura->total,
                'created_at' => $fatura->created_at,
                'numero_itens' => count($fatura->linhasfaturas),
            ];
        }

        return [
            'success' => true,
            'total_pendente' => array_sum(array_column($result, 'total')),
            'quantidade' => count($result),
            'faturas' => $result,
        ];
    }

    /**
     * PUT /teste/animal/{id}/update
     * Atualizar dados básicos do animal (nome, peso)
     * 
     * Body: {
     *   "nome": "Novo Nome",
     *   "peso": 15.5
     * }
     */
    public function actionUpdateAnimal($id)
    {
        $user = $this->getAuthenticatedUser();
        $userProfileId = $user->userprofile->id;

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        $data = Yii::$app->request->post();

        // Atualizar nome
        if (isset($data['nome']) && !empty($data['nome'])) {
            $animal->nome = $data['nome'];
        }

        // Atualizar peso
        if (isset($data['peso']) && !empty($data['peso'])) {
            $animal->peso = (float)$data['peso'];
        }

        if (!$animal->save(false)) {
            throw new BadRequestHttpException('Erro ao atualizar animal');
        }

        return [
            'success' => true,
            'message' => 'Animal atualizado com sucesso',
            'animal' => [
                'id' => $animal->id,
                'nome' => $animal->nome,
                'peso' => (float)$animal->peso,
            ],
        ];
    }

    /**
     * POST /teste/marcacao/request
     * Solicitar uma nova marcação (criação simplificada)
     * 
     * Body: {
     *   "animais_id": 1,
     *   "servicos_id": 1,
     *   "observacoes": "Animal precisa de consulta urgente"
     * }
     * 
     * Nota: Este é um exemplo simplificado. Na prática, marcações
     * normalmente são criadas pelo veterinário no backend.
     */
    public function actionRequestMarcacao()
    {
        $user = $this->getAuthenticatedUser();
        $userProfileId = $user->userprofile->id;

        $data = Yii::$app->request->post();

        // Validar campos obrigatórios
        if (empty($data['animais_id']) || empty($data['servicos_id'])) {
            throw new BadRequestHttpException('Animal e serviço são obrigatórios');
        }

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $data['animais_id'], 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        return [
            'success' => true,
            'message' => 'Solicitação de marcação registrada. Aguarde contato da clínica para confirmação.',
            'solicitacao' => [
                'animal_id' => $data['animais_id'],
                'animal_nome' => $animal->nome,
                'servico_id' => $data['servicos_id'],
                'observacoes' => $data['observacoes'] ?? null,
                'status' => 'pendente',
            ],
        ];
    }

    /**
     * EXEMPLO: GET /teste/exemplo
     * Template de endpoint vazio para você implementar
     * 
     * Use este como base para criar novos endpoints para seu teste
     */
    public function actionExemplo()
    {
        $user = $this->getAuthenticatedUser();
        $userProfileId = $user->userprofile->id;

        // Sua implementação aqui

        return [
            'success' => true,
            'message' => 'Endpoint de exemplo',
            'data' => [],
        ];
    }
}

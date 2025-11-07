<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Animal;
use common\models\Marcacao;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Metodopagamento;
use common\models\Userprofile;
use common\models\Morada;
use common\models\Nota;
use common\models\User;

/**
 * Controller de Cliente
 * 
 * Endpoints protegidos para operações do cliente (dono de animal).
 * Todos os endpoints requerem autenticação via access-token.
 */
class ClientController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Autenticação via QueryParamAuth (access-token)
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'access-token',
        ];

        // CORS
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        // JSON response
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * Obter ID do userprofile do usuário autenticado
     * @return int
     * @throws UnauthorizedHttpException
     */
    protected function getUserProfileId()
    {
        $user = Yii::$app->user->identity;
        if (!$user || !$user->userprofile) {
            throw new UnauthorizedHttpException('Usuário não autenticado ou sem perfil');
        }
        return $user->userprofile->id;
    }

    // ==================== ANIMAIS ====================

    /**
     * GET /client/animal
     * Lista todos os animal do cliente autenticado
     */
    public function actionAnimais()
    {
        $userProfileId = $this->getUserProfileId();

        $animais = Animal::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->all();

        $result = [];
        foreach ($animais as $animal) {
            $idade = null;
            if ($animal->dtanascimento) {
                $nascimento = new \DateTime($animal->dtanascimento);
                $hoje = new \DateTime();
                $idade = $hoje->diff($nascimento)->y;
            }

            $result[] = [
                'id' => $animal->id,
                'nome' => $animal->nome,
                'especie' => $animal->especies ? $animal->especies->nome : null,
                'raca' => $animal->racas ? $animal->racas->nome : null,
                'idade' => $idade,
                'peso' => (float)$animal->peso,
                'genero' => $animal->sexo,
                'datanascimento' => $animal->dtanascimento,
                'microchip' => $animal->microship,
                'foto_url' => $animal->getImageAbsoluteUrl(),
                'donos_id' => $animal->userprofiles_id,
                'ativo' => $animal->eliminado == 0,
            ];
        }

        return $result;
    }

    /**
     * GET /client/animal/{id}
     * Detalhes de um animal específico
     */
    public function actionAnimal($id)
    {
        $userProfileId = $this->getUserProfileId();

        $animal = Animal::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->one();

        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        $idade = null;
        if ($animal->dtanascimento) {
            $nascimento = new \DateTime($animal->dtanascimento);
            $hoje = new \DateTime();
            $idade = $hoje->diff($nascimento)->y;
        }

        // Buscar notas do animal
        $notas = [];
        foreach ($animal->notas as $nota) {
            $notas[] = [
                'id' => $nota->id,
                'texto' => $nota->nota,
                'data_criacao' => $nota->created_at,
                'data_atualizacao' => $nota->updated_at,
                'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : 'N/A',
            ];
        }

        return [
            'id' => $animal->id,
            'nome' => $animal->nome,
            'especie' => $animal->especies ? $animal->especies->nome : null,
            'raca' => $animal->racas ? $animal->racas->nome : null,
            'idade' => $idade,
            'peso' => (float)$animal->peso,
            'genero' => $animal->sexo,
            'datanascimento' => $animal->dtanascimento,
            'microchip' => $animal->microship,
            'foto_url' => $animal->getImageAbsoluteUrl(),
            'notas' => $notas,
            'ativo' => $animal->eliminado == 0,
            'dono' => [
                'id' => $animal->userprofiles->id,
                'nomecompleto' => $animal->userprofiles->nomecompleto,
                'telemovel' => $animal->userprofiles->telemovel,
            ],
        ];
    }

    // ==================== MARCAÇÕES ====================

    /**
     * GET /client/marcacao
     * Lista marcações do cliente com filtros opcionais
     */
    public function actionMarcacoes()
    {
        $userProfileId = $this->getUserProfileId();

        $query = Marcacao::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['animal', 'animal.especies']);

        // Filtros
        $status = Yii::$app->request->get('status');
        if ($status) {
            $query->andWhere(['estado' => $status]);
        }

        $animalId = Yii::$app->request->get('animal_id');
        if ($animalId) {
            $query->andWhere(['animais_id' => $animalId]);
        }

        $dataInicio = Yii::$app->request->get('data_inicio');
        if ($dataInicio) {
            $query->andWhere(['>=', 'data', $dataInicio]);
        }

        $dataFim = Yii::$app->request->get('data_fim');
        if ($dataFim) {
            $query->andWhere(['<=', 'data', $dataFim]);
        }

        $search = Yii::$app->request->get('search');
        if ($search) {
            $query->andWhere(['or',
                ['like', 'tipo', $search],
                ['like', 'diagnostico', $search],
            ]);
        }

        $marcacoes = $query->orderBy(['data' => SORT_DESC, 'horainicio' => SORT_DESC])->all();

        $result = [];
        foreach ($marcacoes as $marcacao) {
            $result[] = [
                'id' => $marcacao->id,
                'data' => $marcacao->data,
                'hora' => substr($marcacao->horainicio, 0, 5), // HH:MM
                'tipo' => $marcacao->tipo,
                'status' => ucfirst($marcacao->estado),
                'duracao_minutos' => $this->calcularDuracao($marcacao->horainicio, $marcacao->horafim),
                'observacoes' => $marcacao->diagnostico,
                'animal_id' => $marcacao->animais_id,
                'animal_nome' => $marcacao->animais ? $marcacao->animais->nome : null,
                'animal_especie' => $marcacao->animais && $marcacao->animais->especies ? $marcacao->animais->especies->nome : null,
                'veterinario_nome' => 'Dr. Veterinário', // TODO: Adicionar relação com veterinário
                'veterinario_especialidade' => 'Clínica Geral',
                'clinica_nome' => 'Clínica VetGestLink', // TODO: Adicionar relação com clínica
                'clinica_morada' => 'Rua Principal, Lisboa',
            ];
        }

        return $result;
    }

    /**
     * GET /client/marcacao/{id}
     * Detalhes de uma marcação específica
     */
    public function actionMarcacao($id)
    {
        $userProfileId = $this->getUserProfileId();

        $marcacao = Marcacao::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['animal', 'animal.especies', 'animal.racas'])
            ->one();

        if (!$marcacao) {
            throw new NotFoundHttpException('Marcação não encontrada');
        }

        $idade = null;
        if ($marcacao->animais && $marcacao->animais->dtanascimento) {
            $nascimento = new \DateTime($marcacao->animais->dtanascimento);
            $hoje = new \DateTime();
            $idade = $hoje->diff($nascimento)->y;
        }

        return [
            'id' => $marcacao->id,
            'data' => $marcacao->data,
            'hora' => substr($marcacao->horainicio, 0, 5),
            'tipo' => $marcacao->tipo,
            'status' => ucfirst($marcacao->estado),
            'duracao_minutos' => $this->calcularDuracao($marcacao->horainicio, $marcacao->horafim),
            'observacoes' => $marcacao->diagnostico,
            'valor_estimado' => (float)$marcacao->preco,
            'animal' => [
                'id' => $marcacao->animais->id,
                'nome' => $marcacao->animais->nome,
                'especie' => $marcacao->animais->especies ? $marcacao->animais->especies->nome : null,
                'raca' => $marcacao->animais->racas ? $marcacao->animais->racas->nome : null,
                'idade' => $idade,
            ],
            'veterinario' => [
                'id' => 1,
                'nome' => 'Dr. Veterinário',
                'especialidade' => 'Clínica Geral',
                'crmv' => '12345',
            ],
            'clinica' => [
                'id' => 1,
                'nome' => 'Clínica VetGestLink',
                'morada' => 'Rua Principal, 123',
                'localidade' => 'Lisboa',
                'telefone' => '+351 21 123 4567',
                'email' => 'geral@vetgestlink.com',
            ],
        ];
    }

    // ==================== FATURAS ====================

    /**
     * GET /client/fatura
     * Lista fatura do cliente com filtros opcionais
     */
    public function actionFaturas()
    {
        $userProfileId = $this->getUserProfileId();

        $query = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['linhasfaturas', 'metodospagamentos']);

        // Filtros
        $status = Yii::$app->request->get('status');
        if ($status) {
            // 0 = Pendente, 1 = Paga
            $estadoMap = [
                'Pendente' => 0,
                'Paga' => 1,
                'Vencida' => 0, // Também pendente mas vencida
            ];
            if (isset($estadoMap[$status])) {
                $query->andWhere(['estado' => $estadoMap[$status]]);
            }
        }

        $ano = Yii::$app->request->get('ano');
        if ($ano) {
            $query->andWhere(['YEAR(data)' => $ano]);
        }

        $faturas = $query->orderBy(['data' => SORT_DESC])->all();

        $result = [];
        foreach ($faturas as $fatura) {
            $statusFatura = $fatura->estado == 1 ? 'Paga' : 'Pendente';
            
            // Verificar se está vencida
            if ($statusFatura == 'Pendente') {
                $dataVencimento = new \DateTime($fatura->data);
                $dataVencimento->modify('+30 days'); // Assumindo 30 dias de prazo
                $hoje = new \DateTime();
                if ($hoje > $dataVencimento) {
                    $statusFatura = 'Vencida';
                }
            }

            // Resumo dos serviços
            $servicos = [];
            foreach ($fatura->linhasfaturas as $linha) {
                $servicos[] = $linha->descricao ?? 'Serviço';
            }
            $servicosResumo = implode(' + ', array_slice($servicos, 0, 2));
            if (count($servicos) > 2) {
                $servicosResumo .= ' + ...';
            }

            $result[] = [
                'id' => $fatura->id,
                'numero' => 'FT' . date('Y', strtotime($fatura->data)) . '/' . str_pad($fatura->id, 3, '0', STR_PAD_LEFT),
                'data_emissao' => $fatura->data,
                'data_vencimento' => date('Y-m-d', strtotime($fatura->data . ' +30 days')),
                'valor_total' => (float)$fatura->total,
                'valor_pago' => $fatura->estado == 1 ? (float)$fatura->total : 0.00,
                'valor_pendente' => $fatura->estado == 1 ? 0.00 : (float)$fatura->total,
                'status' => $statusFatura,
                'servicos_resumo' => $servicosResumo,
                'animal_nome' => null, // TODO: Relacionar com animal
                'data_pagamento' => $fatura->estado == 1 ? $fatura->data : null,
                'metodo_pagamento' => $fatura->metodospagamentos ? $fatura->metodospagamentos->nome : null,
            ];
        }

        return $result;
    }

    /**
     * GET /client/fatura/{id}
     * Detalhes de uma fatura específica
     */
    public function actionFatura($id)
    {
        $userProfileId = $this->getUserProfileId();

        $fatura = Fatura::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['linhasfaturas', 'metodospagamentos'])
            ->one();

        if (!$fatura) {
            throw new NotFoundHttpException('Fatura não encontrada');
        }

        $statusFatura = $fatura->estado == 1 ? 'Paga' : 'Pendente';

        $itens = [];
        foreach ($fatura->linhasfaturas as $linha) {
            $itens[] = [
                'id' => $linha->id,
                'descricao' => $linha->descricao ?? 'Serviço',
                'quantidade' => (int)$linha->quantidade,
                'preco_unitario' => (float)$linha->preco,
                'subtotal' => (float)($linha->quantidade * $linha->preco),
            ];
        }

        $pagamentos = [];
        if ($fatura->estado == 1) {
            $pagamentos[] = [
                'id' => 1,
                'data' => $fatura->data,
                'valor' => (float)$fatura->total,
                'metodo' => $fatura->metodospagamentos ? $fatura->metodospagamentos->nome : 'Não especificado',
                'referencia' => 'REF' . $fatura->id,
            ];
        }

        return [
            'id' => $fatura->id,
            'numero' => 'FT' . date('Y', strtotime($fatura->data)) . '/' . str_pad($fatura->id, 3, '0', STR_PAD_LEFT),
            'data_emissao' => $fatura->data,
            'data_vencimento' => date('Y-m-d', strtotime($fatura->data . ' +30 days')),
            'valor_total' => (float)$fatura->total,
            'valor_pago' => $fatura->estado == 1 ? (float)$fatura->total : 0.00,
            'valor_pendente' => $fatura->estado == 1 ? 0.00 : (float)$fatura->total,
            'status' => $statusFatura,
            'itens' => $itens,
            'pagamentos' => $pagamentos,
        ];
    }

    /**
     * POST /client/fatura/{id}/pagamento
     * Processar pagamento de uma fatura
     */
    public function actionPagamento($id)
    {
        $userProfileId = $this->getUserProfileId();

        $fatura = Fatura::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->one();

        if (!$fatura) {
            throw new NotFoundHttpException('Fatura não encontrada');
        }

        if ($fatura->estado == 1) {
            throw new BadRequestHttpException('Fatura já está paga');
        }

        $data = Yii::$app->request->post();
        $metodoPagamentoId = $data['metodospagamentos_id'] ?? null;
        $valor = $data['valor'] ?? null;
        $referencia = $data['referencia'] ?? null;

        if (!$metodoPagamentoId || !$valor) {
            throw new BadRequestHttpException('Método de pagamento e valor são obrigatórios');
        }

        // Validar método de pagamento
        $metodoPagamento = Metodopagamento::findOne(['id' => $metodoPagamentoId, 'vigor' => 1, 'eliminado' => 0]);
        if (!$metodoPagamento) {
            throw new BadRequestHttpException('Método de pagamento inválido');
        }

        // Atualizar fatura
        $fatura->estado = 1; // Paga
        $fatura->metodospagamentos_id = $metodoPagamentoId;
        
        if (!$fatura->save()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao processar pagamento');
        }

        return [
            'success' => true,
            'message' => 'Pagamento processado com sucesso',
            'pagamento' => [
                'id' => $fatura->id,
                'valor' => (float)$valor,
                'metodo' => $metodoPagamento->nome,
                'referencia' => $referencia ?? 'REF' . $fatura->id,
                'data' => date('Y-m-d'),
            ],
            'fatura' => [
                'id' => $fatura->id,
                'numero' => 'FT' . date('Y', strtotime($fatura->data)) . '/' . str_pad($fatura->id, 3, '0', STR_PAD_LEFT),
                'valor_total' => (float)$fatura->total,
                'valor_pago' => (float)$fatura->total,
                'valor_pendente' => 0.00,
                'status' => 'Paga',
            ],
        ];
    }

    /**
     * GET /client/fatura/resumo
     * Resumo financeiro do cliente
     */
    public function actionResumo()
    {
        $userProfileId = $this->getUserProfileId();
        $ano = Yii::$app->request->get('ano', date('Y'));

        // Total pendente
        $totalPendente = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'estado' => 0, 'eliminado' => 0])
            ->sum('total') ?? 0.00;

        // Total pago no ano
        $totalPagoAno = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'estado' => 1, 'eliminado' => 0])
            ->andWhere(['YEAR(data)' => $ano])
            ->sum('total') ?? 0.00;

        // Contagem de fatura pendentes
        $totalFaturasPendentes = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'estado' => 0, 'eliminado' => 0])
            ->count();

        // Contagem de fatura pagas no ano
        $totalFaturasPagasAno = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'estado' => 1, 'eliminado' => 0])
            ->andWhere(['YEAR(data)' => $ano])
            ->count();

        // Próxima fatura a vencer
        $proximaFatura = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'estado' => 0, 'eliminado' => 0])
            ->orderBy(['data' => SORT_ASC])
            ->one();

        $proximaFaturaVencimento = null;
        if ($proximaFatura) {
            $proximaFaturaVencimento = date('Y-m-d', strtotime($proximaFatura->data . ' +30 days'));
        }

        return [
            'total_pendente' => (float)$totalPendente,
            'total_pago_ano' => (float)$totalPagoAno,
            'total_faturas_pendentes' => (int)$totalFaturasPendentes,
            'total_faturas_pagas_ano' => (int)$totalFaturasPagasAno,
            'proxima_fatura_vencimento' => $proximaFaturaVencimento,
            'ano' => (int)$ano,
        ];
    }

    // ==================== MÉTODOS DE PAGAMENTO ====================

    /**
     * GET /client/metodos-pagamento
     * Lista métodos de pagamento disponíveis
     */
    public function actionMetodosPagamento()
    {
        $metodos = Metodopagamento::find()
            ->where(['vigor' => 1, 'eliminado' => 0])
            ->all();

        $result = [];
        foreach ($metodos as $metodo) {
            // Mapear ícones baseado no nome
            $icone = 'card';
            if (stripos($metodo->nome, 'mbway') !== false) {
                $icone = 'mbway';
            } elseif (stripos($metodo->nome, 'multibanco') !== false) {
                $icone = 'multibanco';
            }

            $result[] = [
                'id' => $metodo->id,
                'nome' => $metodo->nome,
                'descricao' => 'Pagamento via ' . $metodo->nome,
                'icone' => $icone,
                'ativo' => true,
                'taxa' => 0.00,
            ];
        }

        return $result;
    }

    // ==================== PERFIL ====================

    /**
     * GET /client/perfil
     * Obter perfil completo do cliente
     */
    public function actionPerfil()
    {
        $userProfileId = $this->getUserProfileId();

        $userprofile = Userprofile::find()
            ->where(['id' => $userProfileId, 'eliminado' => 0])
            ->with(['animal', 'moradas', 'user'])
            ->one();

        if (!$userprofile) {
            throw new NotFoundHttpException('Perfil não encontrado');
        }

        // Animais
        $animais = [];
        foreach ($userprofile->animais as $animal) {
            if ($animal->eliminado == 0) {
                $idade = null;
                if ($animal->dtanascimento) {
                    $nascimento = new \DateTime($animal->dtanascimento);
                    $hoje = new \DateTime();
                    $idade = $hoje->diff($nascimento)->y;
                }

                $animais[] = [
                    'id' => $animal->id,
                    'nome' => $animal->nome,
                    'especie' => $animal->especies ? $animal->especies->nome : null,
                    'raca' => $animal->racas ? $animal->racas->nome : null,
                    'idade' => $idade,
                    'peso' => (float)$animal->peso,
                    'genero' => $animal->sexo,
                    'foto_url' => $animal->getImageAbsoluteUrl(),
                ];
            }
        }

        // Morada principal
        $moradaPrincipal = null;
        foreach ($userprofile->moradas as $morada) {
            if ($morada->principal == 1 && $morada->eliminado == 0) {
                $moradaPrincipal = [
                    'id' => $morada->id,
                    'rua' => $morada->rua,
                    'nporta' => $morada->nporta,
                    'cdpostal' => $morada->cdpostal,
                    'localidade' => $morada->localidade,
                    'cidade' => $morada->cidade,
                    'principal' => true,
                ];
                break;
            }
        }

        // Estatísticas
        $totalConsultas = Marcacao::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->count();

        $proximaConsulta = Marcacao::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->andWhere(['estado' => 'pendente'])
            ->andWhere(['>=', 'data', date('Y-m-d')])
            ->orderBy(['data' => SORT_ASC])
            ->one();

        $faturasPendentes = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'estado' => 0, 'eliminado' => 0])
            ->count();

        $valorPendente = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'estado' => 0, 'eliminado' => 0])
            ->sum('total') ?? 0.00;

        return [
            'id' => $userprofile->id,
            'nomecompleto' => $userprofile->nomecompleto,
            'email' => $userprofile->user ? $userprofile->user->email : null,
            'telemovel' => $userprofile->telemovel,
            'nif' => $userprofile->nif,
            'datanascimento' => $userprofile->dtanascimento,
            'genero' => null, // TODO: Adicionar campo gênero
            'foto_url' => null, // TODO: Adicionar foto de usuário
            'ativo' => $userprofile->eliminado == 0,
            'animal' => $animais,
            'morada' => $moradaPrincipal,
            'estatisticas' => [
                'total_animais' => count($animais),
                'total_consultas' => (int)$totalConsultas,
                'proxima_consulta' => $proximaConsulta ? $proximaConsulta->data : null,
                'faturas_pendentes' => (int)$faturasPendentes,
                'valor_pendente' => (float)$valorPendente,
            ],
        ];
    }

    /**
     * PUT /client/perfil
     * Atualizar perfil do cliente
     */
    public function actionUpdatePerfil()
    {
        $userProfileId = $this->getUserProfileId();

        $userprofile = Userprofile::findOne(['id' => $userProfileId, 'eliminado' => 0]);
        if (!$userprofile) {
            throw new NotFoundHttpException('Perfil não encontrado');
        }

        $data = Yii::$app->request->post();

        if (isset($data['nomecompleto'])) {
            $userprofile->nomecompleto = $data['nomecompleto'];
        }

        if (isset($data['telemovel'])) {
            $userprofile->telemovel = $data['telemovel'];
        }

        if (!$userprofile->save()) {
            throw new BadRequestHttpException('Erro ao atualizar perfil: ' . json_encode($userprofile->errors));
        }

        return [
            'success' => true,
            'message' => 'Perfil atualizado com sucesso',
            'user' => [
                'id' => $userprofile->id,
                'nomecompleto' => $userprofile->nomecompleto,
                'email' => $userprofile->user ? $userprofile->user->email : null,
                'telemovel' => $userprofile->telemovel,
                'updated_at' => date('Y-m-d\TH:i:s\Z'),
            ],
        ];
    }

    // ==================== MORADAS ====================

    /**
     * GET /client/moradas
     * Lista moradas do cliente (mantido para compatibilidade, mas retorna apenas a principal)
     */
    public function actionMoradas()
    {
        $userProfileId = $this->getUserProfileId();

        $moradas = Morada::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->all();

        $result = [];
        foreach ($moradas as $morada) {
            $result[] = [
                'id' => $morada->id,
                'rua' => $morada->rua,
                'nporta' => $morada->nporta,
                'andar' => $morada->andar,
                'cdpostal' => $morada->cdpostal,
                'localidade' => $morada->localidade,
                'cidade' => $morada->cidade,
                'tipo' => 'Residencial',
                'principal' => $morada->principal == 1,
                'donos_id' => $morada->userprofiles_id,
            ];
        }

        return $result;
    }

    /**
     * PUT /client/morada
     * Atualizar morada principal do cliente
     */
    public function actionUpdateMorada()
    {
        $userProfileId = $this->getUserProfileId();

        // Buscar morada principal
        $morada = Morada::findOne(['userprofiles_id' => $userProfileId, 'principal' => 1, 'eliminado' => 0]);
        
        if (!$morada) {
            // Se não existe, criar nova
            $morada = new Morada();
            $morada->userprofiles_id = $userProfileId;
            $morada->principal = 1;
        }

        $data = Yii::$app->request->post();

        if (isset($data['rua'])) {
            $morada->rua = $data['rua'];
        }

        if (isset($data['nporta'])) {
            $morada->nporta = $data['nporta'];
        }

        if (isset($data['andar'])) {
            $morada->andar = $data['andar'];
        }

        if (isset($data['cdpostal'])) {
            $morada->cdpostal = $data['cdpostal'];
        }

        if (isset($data['localidade'])) {
            $morada->localidade = $data['localidade'];
        }

        if (isset($data['cidade'])) {
            $morada->cidade = $data['cidade'];
        }

        if (!$morada->save()) {
            throw new BadRequestHttpException('Erro ao atualizar morada: ' . json_encode($morada->errors));
        }

        return [
            'success' => true,
            'message' => 'Morada atualizada com sucesso',
            'morada' => [
                'id' => $morada->id,
                'rua' => $morada->rua,
                'nporta' => $morada->nporta,
                'cdpostal' => $morada->cdpostal,
                'localidade' => $morada->localidade,
                'cidade' => $morada->cidade,
                'updated_at' => date('Y-m-d\TH:i:s\Z'),
            ],
        ];
    }

    // ==================== NOTAS ====================

    /**
     * GET /client/animal/{animal_id}/notas
     * Lista notas de um animal específico
     */
    public function actionNotas($animal_id)
    {
        $userProfileId = $this->getUserProfileId();

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $animal_id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        $notas = Nota::find()
            ->where(['animais_id' => $animal_id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($notas as $nota) {
            $result[] = [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'donos_id' => $nota->userprofiles_id,
                'created_at' => date('Y-m-d\TH:i:s\Z', strtotime($nota->created_at)),
                'updated_at' => date('Y-m-d\TH:i:s\Z', strtotime($nota->updated_at)),
            ];
        }

        return $result;
    }

    /**
     * POST /client/animal/{animal_id}/notas
     * Criar nova nota para um animal
     */
    public function actionCreateNota($animal_id)
    {
        $userProfileId = $this->getUserProfileId();

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $animal_id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        $data = Yii::$app->request->post();
        $textoNota = $data['nota'] ?? null;

        if (!$textoNota) {
            throw new BadRequestHttpException('Texto da nota é obrigatório');
        }

        $nota = new Nota();
        $nota->nota = $textoNota;
        $nota->animais_id = $animal_id;
        $nota->userprofiles_id = $userProfileId;

        if (!$nota->save()) {
            throw new BadRequestHttpException('Erro ao criar nota: ' . json_encode($nota->errors));
        }

        return [
            'success' => true,
            'message' => 'Nota criada com sucesso',
            'nota' => [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'donos_id' => $nota->userprofiles_id,
                'created_at' => date('Y-m-d\TH:i:s\Z', strtotime($nota->created_at)),
            ],
        ];
    }

    /**
     * PUT /client/notas/{id}
     * Atualizar uma nota existente
     */
    public function actionUpdateNota($id)
    {
        $userProfileId = $this->getUserProfileId();

        $nota = Nota::findOne(['id' => $id, 'userprofiles_id' => $userProfileId]);
        if (!$nota) {
            throw new NotFoundHttpException('Nota não encontrada');
        }

        $data = Yii::$app->request->post();
        $textoNota = $data['nota'] ?? null;

        if (!$textoNota) {
            throw new BadRequestHttpException('Texto da nota é obrigatório');
        }

        $nota->nota = $textoNota;

        if (!$nota->save()) {
            throw new BadRequestHttpException('Erro ao atualizar nota: ' . json_encode($nota->errors));
        }

        return [
            'success' => true,
            'message' => 'Nota atualizada com sucesso',
            'nota' => [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'donos_id' => $nota->userprofiles_id,
                'updated_at' => date('Y-m-d\TH:i:s\Z', strtotime($nota->updated_at)),
            ],
        ];
    }

    /**
     * DELETE /client/notas/{id}
     * Deletar uma nota (hard delete - tabela notas não tem soft delete)
     */
    public function actionDeleteNota($id)
    {
        $userProfileId = $this->getUserProfileId();

        $nota = Nota::findOne(['id' => $id, 'userprofiles_id' => $userProfileId]);
        if (!$nota) {
            throw new NotFoundHttpException('Nota não encontrada');
        }

        // Hard delete (tabela notas não tem campo eliminado)
        if (!$nota->delete()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao deletar nota');
        }

        return [
            'success' => true,
            'message' => 'Nota deletada com sucesso',
        ];
    }

    // ==================== HELPERS ====================

    /**
     * Calcula duração em minutos entre dois horários
     */
    private function calcularDuracao($inicio, $fim)
    {
        try {
            $dt1 = new \DateTime($inicio);
            $dt2 = new \DateTime($fim);
            $diff = $dt1->diff($dt2);
            return ($diff->h * 60) + $diff->i;
        } catch (\Exception $e) {
            return 30; // Duração padrão
        }
    }
}


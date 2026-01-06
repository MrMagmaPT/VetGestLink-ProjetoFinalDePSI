<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Metodopagamento;

/**
 * Controller de Faturas
 * Endpoints para gerenciar faturas do cliente autenticado
 */
class FaturaController extends ActiveController
{
    public $modelClass = 'common\models\Fatura';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        // Autenticação customizada
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
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

    //Tira as ações padrões do ActiveController (index, view, create, update, delete)
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    protected function getUserProfileId()
    {
        $user = Yii::$app->user->identity;
        if (!$user || !$user->userprofile) {
            throw new UnauthorizedHttpException('Usuário sem perfil associado');
        }
        return $user->userprofile->id;
    }

    /**
     * GET /fatura/all
     * Lista faturas do cliente com filtros opcionais
     */
    public function actionAll()
    {
        $permission = Yii::$app->user->can('viewInvoices');

        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver faturas.');
        }

        $userProfileId = $this->getUserProfileId();

        $faturas = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['linhasfaturas', 'metodospagamentos'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($faturas as $fatura) {
            $result[] = [
                'id' => $fatura->id,
                'total' => (float)$fatura->total,
                'estado' => $fatura->estado,
                'metodospagamentos_id' => $fatura->metodospagamentos_id,
                'metodo_pagamento' => $fatura->metodospagamentos ? $fatura->metodospagamentos->nome : null,
                'userprofiles_id' => $fatura->userprofiles_id,
                'created_at' => $fatura->created_at,
                'numero_itens' => count($fatura->linhasfaturas),
            ];
        }

        return $result;
    }

    /**
     * GET /fatura/view/{id}
     * Detalhes de uma fatura específica
     */
    public function actionView($id)
    {
        $permission = Yii::$app->user->can('viewInvoices');

        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver faturas.');
        }

        $userProfileId = $this->getUserProfileId();

        $fatura = Fatura::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['linhasfaturas.medicamentos', 'linhasfaturas.marcacoes', 'metodospagamentos', 'userprofiles'])
            ->one();

        if (!$fatura) {
            throw new NotFoundHttpException('Fatura não encontrada');
        }

        $linhas = [];
        foreach ($fatura->linhasfaturas as $linha) {
            if ($linha->eliminado == 0) {
                // Determinar descrição baseado no tipo de item
                $descricao = '';
                $tipo = '';
                $precoUnitario = 0;
                
                if ($linha->medicamentos_id && $linha->medicamentos) {
                    $descricao = $linha->medicamentos->nome;
                    $tipo = 'medicamento';
                    $precoUnitario = (float)$linha->medicamentos->preco;
                } elseif ($linha->marcacoes_id && $linha->marcacoes) {
                    $descricao = $linha->marcacoes->servicos ? $linha->marcacoes->servicos->nome : 'Serviço';
                    $tipo = 'servico';
                    $precoUnitario = $linha->marcacoes->servicos ? (float)$linha->marcacoes->servicos->valor : 0;
                } else {
                    $descricao = 'Item';
                    $tipo = 'outro';
                    $precoUnitario = (float)$linha->total / (int)$linha->quantidade;
                }
                
                $linhas[] = [
                    'id' => $linha->id,
                    'descricao' => $descricao,
                    'tipo' => $tipo,
                    'quantidade' => (int)$linha->quantidade,
                    'preco_unitario' => $precoUnitario,
                    'total' => (float)$linha->total,
                ];
            }
        }

        return [
            'id' => $fatura->id,
            'total' => (float)$fatura->total,
            'estado' => $fatura->estado,
            'metodospagamentos_id' => $fatura->metodospagamentos_id,
            'metodo_pagamento' => $fatura->metodospagamentos ? $fatura->metodospagamentos->nome : null,
            'userprofiles_id' => $fatura->userprofiles_id,
            'created_at' => $fatura->created_at,
            'cliente' => [
                'id' => $fatura->userprofiles->id,
                'nomecompleto' => $fatura->userprofiles->nomecompleto,
                'nif' => $fatura->userprofiles->nif,
            ],
            'linhas' => $linhas,
        ];
    }


    /**
     * GET /fatura/paymentmethods
     * Lista métodos de pagamento disponíveis
     */
    public function actionPaymentmethods()
    {
        $permission = Yii::$app->user->can('viewInvoices');

        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver métodos de pagamento.');
        }

        $metodos = Metodopagamento::find()
            ->where(['vigor' => 1, 'eliminado' => 0])
            ->all();

        $result = [];
        foreach ($metodos as $metodo) {
            $result[] = [
                'id' => $metodo->id,
                'nome' => $metodo->nome,
                'vigor' => $metodo->vigor,
            ];
        }

        return $result;
    }

    /**
     * PUT /fatura/pay/{id}
     * Pagar uma fatura (alterar estado para pago)
     * 
     * Body: {
     *   "metodospagamentos_id": 1
     * }
     */
    public function actionPay($id)
    {

        $permission = Yii::$app->user->can('payInvoices');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para pagar faturas.');
        }
        $userProfileId = $this->getUserProfileId();

        $fatura = Fatura::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->one();

        if (!$fatura) {
            throw new NotFoundHttpException('Fatura não encontrada');
        }

        // Verificar se a fatura já está paga
        if ($fatura->estado == '1') {
            throw new BadRequestHttpException('Esta fatura já está paga');
        }

        $body = Yii::$app->request->getBodyParams();
        
        // Validar método de pagamento (obrigatório)
        if (!isset($body['metodospagamentos_id'])) {
            throw new BadRequestHttpException('Método de pagamento é obrigatório');
        }

        $metodoPagamento = Metodopagamento::find()
            ->where(['id' => $body['metodospagamentos_id'], 'vigor' => 1, 'eliminado' => 0])
            ->one();

        if (!$metodoPagamento) {
            throw new BadRequestHttpException('Método de pagamento inválido');
        }

        $fatura->metodospagamentos_id = $body['metodospagamentos_id'];

        // Alterar estado para pago ('1')
        $fatura->estado = '1';

        if (!$fatura->save()) {
            throw new BadRequestHttpException('Erro ao atualizar fatura: ' . json_encode($fatura->errors));
        }

        return [
            'success' => true,
            'message' => 'Fatura paga com sucesso',
            'fatura' => [
                'id' => $fatura->id,
                'total' => (float)$fatura->total,
                'estado' => $fatura->estado,
                'metodospagamentos_id' => $fatura->metodospagamentos_id,
                'created_at' => $fatura->created_at,
            ],
        ];
    }

    /**
     * GET /fatura/count
     * Conta total de faturas do cliente
     */
    public function actionCount()
    {
        $permission = Yii::$app->user->can('viewInvoices');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver faturas.');
        }

        $userProfileId = $this->getUserProfileId();
        $count = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->count();
        
        return ['count' => (int)$count];
    }

    /**
     * GET /fatura/total
     * Retorna soma total de todas as faturas
     */
    public function actionTotal()
    {
        $permission = Yii::$app->user->can('viewInvoices');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver faturas.');
        }

        $userProfileId = $this->getUserProfileId();
        $total = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->sum('total');
        
        return [
            'total' => (float)($total ?? 0),
            'moeda' => 'EUR'
        ];
    }

    /**
     * GET /fatura/ano/{ano}
     * Lista faturas de um ano específico com resumo
     */
    public function actionPorano($ano)
    {
        $permission = Yii::$app->user->can('viewInvoices');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver faturas.');
        }

        $userProfileId = $this->getUserProfileId();
        
        $faturas = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->andWhere(['YEAR(created_at)' => $ano])
            ->with(['metodospagamentos'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        $totalGeral = 0;
        $pagas = 0;
        $pendentes = 0;

        foreach ($faturas as $fatura) {
            $result[] = [
                'id' => $fatura->id,
                'total' => (float)$fatura->total,
                'estado' => $fatura->estado,
                'estado_label' => $fatura->estado == '1' ? 'Paga' : 'Pendente',
                'metodo_pagamento' => $fatura->metodospagamentos ? $fatura->metodospagamentos->nome : null,
                'created_at' => $fatura->created_at,
            ];
            
            $totalGeral += $fatura->total;
            if ($fatura->estado == '1') {
                $pagas++;
            } else {
                $pendentes++;
            }
        }

        return [
            'ano' => (int)$ano,
            'resumo' => [
                'total_geral' => (float)$totalGeral,
                'quantidade' => count($result),
                'pagas' => $pagas,
                'pendentes' => $pendentes,
            ],
            'faturas' => $result,
        ];
    }
}


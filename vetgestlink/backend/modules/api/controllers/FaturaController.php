<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Metodopagamento;

/**
 * Controller de Faturas
 *
 * Endpoints para gerenciar faturas do cliente autenticado.
 */
class FaturaController extends ApiController
{

    /**
     * GET /fatura/all
     * Lista faturas do cliente com filtros opcionais
     */
    public function actionAll()
    {
        $userProfileId = $this->getUserProfileId();

        $query = Fatura::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['linhasfaturas', 'metodospagamentos']);

        // Filtros
        $estado = Yii::$app->request->get('estado');
        if ($estado !== null) {
            $query->andWhere(['estado' => $estado]);
        }

        $ano = Yii::$app->request->get('ano');
        if ($ano) {
            $query->andWhere(['YEAR(created_at)' => $ano]);
        }

        $faturas = $query->orderBy(['created_at' => SORT_DESC])->all();

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
                    $precoUnitario = $linha->marcacoes->servicos ? (float)$linha->marcacoes->servicos->preco : 0;
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
        $userProfileId = $this->getUserProfileId();

        $fatura = Fatura::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->one();

        if (!$fatura) {
            throw new NotFoundHttpException('Fatura não encontrada');
        }

        // Verificar se a fatura já está paga
        if ($fatura->estado == 1) {
            throw new BadRequestHttpException('Esta fatura já está paga');
        }

        $body = Yii::$app->request->getBodyParams();
        
        // Validar método de pagamento (opcional, mas recomendado)
        if (isset($body['metodospagamentos_id'])) {
            $metodoPagamento = Metodopagamento::find()
                ->where(['id' => $body['metodospagamentos_id'], 'vigor' => 1, 'eliminado' => 0])
                ->one();

            if (!$metodoPagamento) {
                throw new BadRequestHttpException('Método de pagamento inválido');
            }

            $fatura->metodospagamentos_id = $body['metodospagamentos_id'];
        }

        // Alterar estado para pago (1)
        $fatura->estado = 1;

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
}


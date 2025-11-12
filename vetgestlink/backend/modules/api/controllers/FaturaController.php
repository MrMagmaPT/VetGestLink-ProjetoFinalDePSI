<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Metodopagamento;

/**
 * Controller de Faturas
 *
 * Endpoints para gerenciar faturas do cliente autenticado.
 */
class FaturaController extends Controller
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
     */
    protected function getUserProfileId()
    {
        $user = Yii::$app->user->identity;
        if (!$user || !$user->userprofile) {
            throw new UnauthorizedHttpException('Usuário não autenticado ou sem perfil');
        }
        return $user->userprofile->id;
    }

    /**
     * GET /fatura
     * Lista faturas do cliente com filtros opcionais
     */
    public function actionIndex()
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
     * GET /fatura/{id}
     * Detalhes de uma fatura específica
     */
    public function actionView($id)
    {
        $userProfileId = $this->getUserProfileId();

        $fatura = Fatura::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['linhasfaturas', 'metodospagamentos', 'userprofiles'])
            ->one();

        if (!$fatura) {
            throw new NotFoundHttpException('Fatura não encontrada');
        }

        $linhas = [];
        foreach ($fatura->linhasfaturas as $linha) {
            if ($linha->eliminado == 0) {
                $linhas[] = [
                    'id' => $linha->id,
                    'descricao' => $linha->descricao,
                    'quantidade' => (int)$linha->quantidade,
                    'preco' => (float)$linha->preco,
                    'subtotal' => (float)($linha->quantidade * $linha->preco),
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
     * GET /fatura/metodos-pagamento
     * Lista métodos de pagamento disponíveis
     */
    public function actionMetodosPagamento()
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
}


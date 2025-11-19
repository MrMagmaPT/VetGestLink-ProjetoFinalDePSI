<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Servico;

/**
 * Controller de Serviços
 *
 * Endpoints para listar serviços disponíveis.
 */
class ServicoController extends Controller
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
     * GET /servico
     * Lista todos os serviços disponíveis (não eliminados)
     */
    public function actionIndex()
    {
        $query = Servico::find()
            ->where(['eliminado' => 0])
            ->orderBy(['nome' => SORT_ASC]);

        // Filtro por nome (busca)
        $search = Yii::$app->request->get('search');
        if ($search) {
            $query->andWhere(['like', 'nome', $search]);
        }

        $servicos = $query->all();

        $result = [];
        foreach ($servicos as $servico) {
            $result[] = [
                'id' => $servico->id,
                'nome' => $servico->nome,
                'valor' => (float)$servico->valor,
            ];
        }

        return $result;
    }

    /**
     * GET /servico/{id}
     * Detalhes de um serviço específico
     */
    public function actionView($id)
    {
        $servico = Servico::find()
            ->where(['id' => $id, 'eliminado' => 0])
            ->one();

        if (!$servico) {
            throw new NotFoundHttpException('Serviço não encontrado');
        }

        return [
            'id' => $servico->id,
            'nome' => $servico->nome,
            'valor' => (float)$servico->valor,
        ];
    }
}


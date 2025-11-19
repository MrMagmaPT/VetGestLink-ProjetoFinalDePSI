<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\auth\QueryParamAuth;
use common\models\Especie;
use common\models\Raca;

/**
 * Controller de Espécies e Raças
 *
 * Endpoints para obter listas de espécies e raças (dados mestres).
 */
class EspecieController extends Controller
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
                'Access-Control-Request-Method' => ['GET', 'OPTIONS'],
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
     * GET /especie
     * Lista todas as espécies disponíveis
     */
    public function actionIndex()
    {
        $especies = Especie::find()
            ->where(['eliminado' => 0])
            ->orderBy(['nome' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($especies as $especie) {
            $result[] = [
                'id' => $especie->id,
                'nome' => $especie->nome,
            ];
        }

        return $result;
    }

    /**
     * GET /especie/{id}/racas
     * Lista raças de uma espécie específica
     */
    public function actionRacas($id)
    {
        $racas = Raca::find()
            ->where(['especies_id' => $id, 'eliminado' => 0])
            ->orderBy(['nome' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($racas as $raca) {
            $result[] = [
                'id' => $raca->id,
                'nome' => $raca->nome,
                'especies_id' => $raca->especies_id,
            ];
        }

        return $result;
    }
}


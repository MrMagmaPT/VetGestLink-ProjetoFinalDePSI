<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Animal;
use common\models\Nota;

/**
 * Controller de Animais
 *
 * Endpoints para gerenciar animais do cliente autenticado.
 */
class AnimalController extends Controller
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
     * GET /animal
     * Lista todos os animais do cliente autenticado
     */
    public function actionIndex()
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
                'especie_id' => $animal->especies_id,
                'raca' => $animal->racas ? $animal->racas->nome : null,
                'raca_id' => $animal->racas_id,
                'idade' => $idade,
                'peso' => (float)$animal->peso,
                'sexo' => $animal->sexo,
                'datanascimento' => $animal->dtanascimento,
                'microchip' => $animal->microship,
                'foto_url' => $animal->getImageAbsoluteUrl(),
                'userprofiles_id' => $animal->userprofiles_id,
                'ativo' => $animal->eliminado == 0,
            ];
        }

        return $result;
    }

    /**
     * GET /animal/{id}
     * Detalhes de um animal específico
     */
    public function actionView($id)
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
            if ($nota->eliminado == 0) {
                $notas[] = [
                    'id' => $nota->id,
                    'texto' => $nota->nota,
                    'created_at' => $nota->created_at,
                    'updated_at' => $nota->updated_at,
                    'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : 'N/A',
                ];
            }
        }

        return [
            'id' => $animal->id,
            'nome' => $animal->nome,
            'especie' => $animal->especies ? $animal->especies->nome : null,
            'especie_id' => $animal->especies_id,
            'raca' => $animal->racas ? $animal->racas->nome : null,
            'raca_id' => $animal->racas_id,
            'idade' => $idade,
            'peso' => (float)$animal->peso,
            'sexo' => $animal->sexo,
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

    /**
     * GET /animal/{id}/notas
     * Lista notas de um animal específico
     */
    public function actionNotas($id)
    {
        $userProfileId = $this->getUserProfileId();

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        $notas = Nota::find()
            ->where(['animais_id' => $id, 'eliminado' => 0])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($notas as $nota) {
            $result[] = [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'userprofiles_id' => $nota->userprofiles_id,
                'created_at' => $nota->created_at,
                'updated_at' => $nota->updated_at,
                'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : 'N/A',
            ];
        }

        return $result;
    }
}


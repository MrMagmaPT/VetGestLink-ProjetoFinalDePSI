<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Marcacao;

/**
 * Controller de Marcações
 *
 * Endpoints para gerenciar marcações do cliente autenticado.
 */
class MarcacaoController extends Controller
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
     * GET /marcacao
     * Lista marcações do cliente com filtros opcionais
     */
    public function actionIndex()
    {
        $userProfileId = $this->getUserProfileId();

        $query = Marcacao::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['animais', 'animais.especies']);

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
                'horainicio' => $marcacao->horainicio,
                'horafim' => $marcacao->horafim,
                'tipo' => $marcacao->tipo,
                'estado' => $marcacao->estado,
                'duracao_minutos' => $this->calcularDuracao($marcacao->horainicio, $marcacao->horafim),
                'diagnostico' => $marcacao->diagnostico,
                'preco' => (float)$marcacao->preco,
                'animais_id' => $marcacao->animais_id,
                'animal_nome' => $marcacao->animais ? $marcacao->animais->nome : null,
                'animal_especie' => $marcacao->animais && $marcacao->animais->especies ? $marcacao->animais->especies->nome : null,
                'userprofiles_id' => $marcacao->userprofiles_id,
                'created_at' => $marcacao->created_at,
                'updated_at' => $marcacao->updated_at,
            ];
        }

        return $result;
    }

    /**
     * GET /marcacao/{id}
     * Detalhes de uma marcação específica
     */
    public function actionView($id)
    {
        $userProfileId = $this->getUserProfileId();

        $marcacao = Marcacao::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['animais', 'animais.especies', 'animais.racas'])
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
            'horainicio' => $marcacao->horainicio,
            'horafim' => $marcacao->horafim,
            'tipo' => $marcacao->tipo,
            'estado' => $marcacao->estado,
            'duracao_minutos' => $this->calcularDuracao($marcacao->horainicio, $marcacao->horafim),
            'diagnostico' => $marcacao->diagnostico,
            'preco' => (float)$marcacao->preco,
            'animais_id' => $marcacao->animais_id,
            'userprofiles_id' => $marcacao->userprofiles_id,
            'created_at' => $marcacao->created_at,
            'updated_at' => $marcacao->updated_at,
            'animal' => [
                'id' => $marcacao->animais->id,
                'nome' => $marcacao->animais->nome,
                'especie' => $marcacao->animais->especies ? $marcacao->animais->especies->nome : null,
                'raca' => $marcacao->animais->racas ? $marcacao->animais->racas->nome : null,
                'idade' => $idade,
                'peso' => (float)$marcacao->animais->peso,
                'sexo' => $marcacao->animais->sexo,
            ],
        ];
    }

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


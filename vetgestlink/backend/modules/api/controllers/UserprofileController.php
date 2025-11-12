<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Userprofile;
use common\models\Animal;
use common\models\Morada;
use common\models\Marcacao;
use common\models\Fatura;

/**
 * Controller de Perfil de Usuário
 *
 * Endpoints para gerenciar o perfil do cliente autenticado.
 */
class UserprofileController extends Controller
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
     * GET /userprofile
     * Obter perfil completo do cliente autenticado
     */
    public function actionIndex()
    {
        $userProfileId = $this->getUserProfileId();

        $userprofile = Userprofile::find()
            ->where(['id' => $userProfileId, 'eliminado' => 0])
            ->with(['animais', 'moradas', 'user'])
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
                    'sexo' => $animal->sexo,
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
            'dtanascimento' => $userprofile->dtanascimento,
            'foto_url' => null, // TODO: Adicionar foto de usuário
            'ativo' => $userprofile->eliminado == 0,
            'animais' => $animais,
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
     * PUT /userprofile
     * Atualizar perfil do cliente autenticado
     */
    public function actionUpdate()
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

        if (isset($data['dtanascimento'])) {
            $userprofile->dtanascimento = $data['dtanascimento'];
        }

        if (!$userprofile->save()) {
            throw new BadRequestHttpException('Erro ao atualizar perfil: ' . json_encode($userprofile->errors));
        }

        return [
            'success' => true,
            'message' => 'Perfil atualizado com sucesso',
            'userprofile' => [
                'id' => $userprofile->id,
                'nomecompleto' => $userprofile->nomecompleto,
                'email' => $userprofile->user ? $userprofile->user->email : null,
                'telemovel' => $userprofile->telemovel,
                'dtanascimento' => $userprofile->dtanascimento,
            ],
        ];
    }
}


<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
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

        // CORS - DEVE vir PRIMEIRO
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
     * GET /marcacao/all
     * Lista marcações do cliente com filtros opcionais
     */
    public function actionAll()
    {
        $permission = Yii::$app->user->can('viewAppointments');
        // Verifica permissão   
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver marcações.');
        }


        $userProfileId = $this->getUserProfileId();

        $marcacoes = Marcacao::find()
            ->joinWith(['animais'])
            ->where(['animais.userprofiles_id' => $userProfileId, 'marcacoes.eliminado' => 0])
            ->with(['animais.especies', 'servicos'])
            ->orderBy(['marcacoes.data' => SORT_DESC, 'marcacoes.horainicio' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($marcacoes as $marcacao) {
            $result[] = [
                'id' => $marcacao->id,
                'data' => $marcacao->data,
                'horainicio' => $marcacao->horainicio,
                'horafim' => $marcacao->horafim,
                'estado' => $marcacao->estado,
                'duracao_minutos' => $this->calcularDuracao($marcacao->horainicio, $marcacao->horafim),
                'diagnostico' => $marcacao->diagnostico,
                'servico_nome' => $marcacao->servicos ? $marcacao->servicos->nome : null,
                'animal_nome' => $marcacao->animais ? $marcacao->animais->nome : null,
                'animal_especie' => $marcacao->animais && $marcacao->animais->especies ? $marcacao->animais->especies->nome : null,
                'created_at' => $marcacao->created_at,
                'updated_at' => $marcacao->updated_at,
            ];
        }

        return $result;
    }

    /**
     * GET /marcacao/view/{id}
     * Detalhes de uma marcação específica
     */ 
    public function actionView($id)
    {
        $permission = Yii::$app->user->can('viewAppointments');
        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver marcações.');
        }
        
        $userProfileId = $this->getUserProfileId();

        $marcacao = Marcacao::find()
            ->joinWith(['animais'])
            ->where(['marcacoes.id' => $id, 'animais.userprofiles_id' => $userProfileId, 'marcacoes.eliminado' => 0])
            ->with(['animais.especies', 'animais.racas', 'servicos'])
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
            'estado' => $marcacao->estado,
            'duracao_minutos' => $this->calcularDuracao($marcacao->horainicio, $marcacao->horafim),
            'diagnostico' => $marcacao->diagnostico,
            'servicos_id' => $marcacao->servicos_id,
            'servico_nome' => $marcacao->servicos ? $marcacao->servicos->nome : null,
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
     * GET /marcacao/count
     * Conta total de marcações do cliente
     */
    public function actionCount()
    {
        $permission = Yii::$app->user->can('viewAppointments');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver marcações.');
        }

        $userProfileId = $this->getUserProfileId();
        $count = Marcacao::find()
            ->joinWith(['animais'])
            ->where(['animais.userprofiles_id' => $userProfileId, 'marcacoes.eliminado' => 0])
            ->count();
        
        return ['count' => (int)$count];
    }

    /**
     * GET /marcacao/estado/{estado}
     * Lista marcações por estado (pendente, realizada, cancelada)
     */
    public function actionPorestado($estado)
    {
        $permission = Yii::$app->user->can('viewAppointments');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver marcações.');
        }

        $userProfileId = $this->getUserProfileId();
        
        $marcacoes = Marcacao::find()
            ->joinWith(['animais'])
            ->where([
                'animais.userprofiles_id' => $userProfileId,
                'marcacoes.estado' => $estado,
                'marcacoes.eliminado' => 0
            ])
            ->with(['servicos'])
            ->orderBy(['marcacoes.data' => SORT_DESC, 'marcacoes.horainicio' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($marcacoes as $marcacao) {
            $result[] = [
                'id' => $marcacao->id,
                'data' => $marcacao->data,
                'horainicio' => $marcacao->horainicio,
                'horafim' => $marcacao->horafim,
                'estado' => $marcacao->estado,
                'animal_nome' => $marcacao->animais ? $marcacao->animais->nome : null,
                'servico_nome' => $marcacao->servicos ? $marcacao->servicos->nome : null,
                'diagnostico' => $marcacao->diagnostico,
            ];
        }

        return $result;
    }

    /**
     * GET /marcacao/data/{ano}/{mes}/{dia}
     * Lista marcações de uma data específica
     */
    public function actionPordata($ano, $mes, $dia)
    {
        $permission = Yii::$app->user->can('viewAppointments');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver marcações.');
        }

        $userProfileId = $this->getUserProfileId();
        $data = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);

        $marcacoes = Marcacao::find()
            ->joinWith(['animais'])
            ->where([
                'animais.userprofiles_id' => $userProfileId,
                'marcacoes.data' => $data,
                'marcacoes.eliminado' => 0
            ])
            ->with(['servicos'])
            ->orderBy(['marcacoes.horainicio' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($marcacoes as $marcacao) {
            $result[] = [
                'id' => $marcacao->id,
                'horainicio' => $marcacao->horainicio,
                'horafim' => $marcacao->horafim,
                'estado' => $marcacao->estado,
                'duracao_minutos' => $this->calcularDuracao($marcacao->horainicio, $marcacao->horafim),
                'animal_nome' => $marcacao->animais ? $marcacao->animais->nome : null,
                'servico_nome' => $marcacao->servicos ? $marcacao->servicos->nome : null,
                'diagnostico' => $marcacao->diagnostico,
            ];
        }

        return [
            'data' => $data,
            'total' => count($result),
            'marcacoes' => $result,
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


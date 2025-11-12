<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Morada;

/**
 * Controller de Moradas
 *
 * Endpoints para gerenciar moradas do cliente autenticado.
 */
class MoradaController extends Controller
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
     * POST /morada
     * Criar nova morada para o utilizador autenticado
     */
    public function actionCreate()
    {
        $userProfileId = $this->getUserProfileId();

        $data = Yii::$app->request->post();

        // Validar campos obrigatórios
        if (empty($data['rua']) || empty($data['nporta']) || empty($data['cdpostal']) || empty($data['localidade'])) {
            throw new BadRequestHttpException('Campos obrigatórios: rua, nporta, cdpostal, localidade');
        }

        $morada = new Morada();
        $morada->userprofiles_id = $userProfileId;
        $morada->rua = $data['rua'];
        $morada->nporta = $data['nporta'];
        $morada->cdpostal = $data['cdpostal'];
        $morada->localidade = $data['localidade'];
        $morada->cidade = $data['cidade'] ?? null;
        $morada->principal = isset($data['principal']) ? (int)$data['principal'] : 0;
        $morada->eliminado = 0;

        // Se for morada principal, desmarcar outras moradas principais
        if ($morada->principal == 1) {
            Morada::updateAll(
                ['principal' => 0],
                ['userprofiles_id' => $userProfileId, 'eliminado' => 0]
            );
        }

        if (!$morada->save()) {
            Yii::error('Erro ao criar morada: ' . json_encode($morada->errors), __METHOD__);
            throw new BadRequestHttpException('Erro ao criar morada: ' . json_encode($morada->errors));
        }

        return [
            'success' => true,
            'message' => 'Morada criada com sucesso',
            'morada' => [
                'id' => $morada->id,
                'rua' => $morada->rua,
                'nporta' => $morada->nporta,
                'cdpostal' => $morada->cdpostal,
                'localidade' => $morada->localidade,
                'cidade' => $morada->cidade,
                'principal' => (bool)$morada->principal,
            ],
        ];
    }

    /**
     * PUT /morada/{id}
     * Atualizar morada existente do utilizador autenticado
     */
    public function actionUpdate($id)
    {
        $userProfileId = $this->getUserProfileId();

        $morada = Morada::findOne([
            'id' => $id,
            'userprofiles_id' => $userProfileId,
            'eliminado' => 0
        ]);

        if (!$morada) {
            throw new NotFoundHttpException('Morada não encontrada');
        }

        $data = Yii::$app->request->getBodyParams();

        if (isset($data['rua'])) {
            $morada->rua = $data['rua'];
        }

        if (isset($data['nporta'])) {
            $morada->nporta = $data['nporta'];
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

        if (isset($data['principal'])) {
            $morada->principal = (int)$data['principal'];

            // Se for morada principal, desmarcar outras moradas principais
            if ($morada->principal == 1) {
                Morada::updateAll(
                    ['principal' => 0],
                    ['and',
                        ['userprofiles_id' => $userProfileId],
                        ['eliminado' => 0],
                        ['!=', 'id', $id]
                    ]
                );
            }
        }

        if (!$morada->save()) {
            Yii::error('Erro ao atualizar morada: ' . json_encode($morada->errors), __METHOD__);
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
                'principal' => (bool)$morada->principal,
            ],
        ];
    }
}


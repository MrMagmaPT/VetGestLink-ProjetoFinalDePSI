<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Nota;
use common\models\Animal;

/**
 * Controller de Notas
 *
 * Endpoints para gerenciar notas dos animais do cliente autenticado.
 */
class NotaController extends Controller
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
     * GET /nota
     * Lista todas as notas dos animais do cliente
     */
    public function actionIndex()
    {
        $userProfileId = $this->getUserProfileId();

        $notas = Nota::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->with(['animais', 'userprofiles'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($notas as $nota) {
            $result[] = [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'animal_nome' => $nota->animais ? $nota->animais->nome : null,
                'userprofiles_id' => $nota->userprofiles_id,
                'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : null,
                'created_at' => $nota->created_at,
                'updated_at' => $nota->updated_at,
            ];
        }

        return $result;
    }


    /**
     * POST /nota
     * Criar nova nota
     */
    public function actionCreate()
    {
        $userProfileId = $this->getUserProfileId();
        $data = Yii::$app->request->post();

        $animaisId = $data['animais_id'] ?? null;
        $textoNota = $data['nota'] ?? null;

        if (!$animaisId || !$textoNota) {
            throw new BadRequestHttpException('Animal e texto da nota são obrigatórios');
        }

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $animaisId, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        $nota = new Nota();
        $nota->nota = $textoNota;
        $nota->animais_id = $animaisId;
        $nota->userprofiles_id = $userProfileId;

        if (!$nota->save()) {
            throw new BadRequestHttpException('Erro ao criar nota: ' . json_encode($nota->errors));
        }

        return [
            'success' => true,
            'message' => 'Nota criada com sucesso',
            'nota' => [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'userprofiles_id' => $nota->userprofiles_id,
                'created_at' => $nota->created_at,
            ],
        ];
    }

    /**
     * PUT /nota/{id}
     * Atualizar uma nota existente
     */
    public function actionUpdate($id)
    {
        $userProfileId = $this->getUserProfileId();

        $nota = Nota::findOne(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$nota) {
            throw new NotFoundHttpException('Nota não encontrada');
        }

        $data = Yii::$app->request->post();
        $textoNota = $data['nota'] ?? null;

        if (!$textoNota) {
            throw new BadRequestHttpException('Texto da nota é obrigatório');
        }

        $nota->nota = $textoNota;

        if (!$nota->save()) {
            throw new BadRequestHttpException('Erro ao atualizar nota: ' . json_encode($nota->errors));
        }

        return [
            'success' => true,
            'message' => 'Nota atualizada com sucesso',
            'nota' => [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'userprofiles_id' => $nota->userprofiles_id,
                'updated_at' => $nota->updated_at,
            ],
        ];
    }

    /**
     * DELETE /nota/{id}
     * Deletar uma nota (soft delete)
     */
    public function actionDelete($id)
    {
        $userProfileId = $this->getUserProfileId();

        $nota = Nota::findOne(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$nota) {
            throw new NotFoundHttpException('Nota não encontrada');
        }

        // Soft delete
        $nota->eliminado = 1;
        if (!$nota->save()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao deletar nota');
        }

        return [
            'success' => true,
            'message' => 'Nota deletada com sucesso',
        ];
    }
}


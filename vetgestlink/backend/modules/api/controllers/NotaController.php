<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use common\models\Nota;
use common\models\Animal;

/**
 * Controller de Notas
 *
 * Endpoints para gerenciar notas dos animais do cliente autenticado.
 */
class NotaController extends ActiveController
{
    public $modelClass = 'common\models\Nota';

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


    //Tira as ações padrões do ActiveController (index, view, create, update, delete)
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
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
     * GET /nota/all
     * Lista todas as notas dos animais do cliente
     */
    public function actionAll()
    {
        $permission = Yii::$app->user->can('viewNotes');
        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver notas.');
        }

        $userProfileId = $this->getUserProfileId();

        $notas = Nota::find()
            ->where(['userprofiles_id' => $userProfileId])
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
            ];
        }

        return $result;
    }

    /**
     * GET /nota/view/{id}
     * Detalhes de uma nota específica
     */
    public function actionView($id)
    {
        $permission = Yii::$app->user->can('viewNotes');
        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver notas.');
        }

        $userProfileId = $this->getUserProfileId();

        $nota = Nota::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId])
            ->with(['animais', 'userprofiles'])
            ->one();

        if (!$nota) {
            throw new NotFoundHttpException('Nota não encontrada');
        }

        return [
            'id' => $nota->id,
            'nota' => $nota->nota,
            'animais_id' => $nota->animais_id,
            'animal_nome' => $nota->animais ? $nota->animais->nome : null,
            'userprofiles_id' => $nota->userprofiles_id,
            'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : null,
            'created_at' => $nota->created_at,
        ];
    }

    /**
     * POST /nota/create
     * Criar nova nota
     */
    public function actionCreate()
    {
        $permission = Yii::$app->user->can('createNotes');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para criar notas.');
        }
        $userProfileId = $this->getUserProfileId();
        $data = Yii::$app->request->post();

        $animaisId = $data['animais_id'] ?? null;
        $textoNota = $data['nota'] ?? null;

        if (!$animaisId || !$textoNota) {
            throw new BadRequestHttpException('Animal e texto da nota são obrigatórios');
        }

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $animaisId, 'userprofiles_id' => $userProfileId]);
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
     * PUT /nota/update/{id}
     * Atualizar uma nota existente
     */
    public function actionUpdate($id)
    {

        $permission = Yii::$app->user->can('updateNotes');
        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para atualizar notas.');
        }


        $userProfileId = $this->getUserProfileId();

        // Verificar se a nota existe
        $notaExiste = Nota::findOne(['id' => $id]);
        if (!$notaExiste) {
            throw new NotFoundHttpException('Nota não encontrada');
        }

        // Verificar se a nota pertence ao usuário
        $nota = Nota::findOne(['id' => $id, 'userprofiles_id' => $userProfileId]);
        if (!$nota) {
            throw new UnauthorizedHttpException('Você só pode atualizar suas próprias notas.');
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
            ],
        ];
    }

    /**
     * DELETE /nota/delete/{id}
     * Deletar uma nota existente
     */
    public function actionDelete($id)
    {

        $permission = Yii::$app->user->can('deleteNotes');
        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para deletar notas.');
        }


        $userProfileId = $this->getUserProfileId();

        // Verificar se a nota existe
        $notaExiste = Nota::findOne(['id' => $id]);
        if (!$notaExiste) {
            throw new NotFoundHttpException('Nota não encontrada');
        }

        // Verificar se a nota pertence ao usuário
        $nota = Nota::findOne(['id' => $id, 'userprofiles_id' => $userProfileId]);
        if (!$nota) {
            throw new UnauthorizedHttpException('Você só pode deletar suas próprias notas.');
        }

        // Delete real
        if (!$nota->delete()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao deletar nota');
        }

        return [
            'success' => true,
            'message' => 'Nota deletada com sucesso',
        ];
    }

    /**
     * GET /nota/count
     * Conta total de notas do cliente
     */
    public function actionCount()
    {
        $permission = Yii::$app->user->can('viewNotes');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver notas.');
        }

        $userProfileId = $this->getUserProfileId();
        $count = Nota::find()
            ->where(['userprofiles_id' => $userProfileId])
            ->count();
        
        return ['count' => (int)$count];
    }

  
}


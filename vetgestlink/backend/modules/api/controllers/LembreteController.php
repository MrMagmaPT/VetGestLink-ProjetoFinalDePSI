<?php

namespace backend\modules\api\controllers;
use common\models\Lembrete;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\web\Response;
use yii\filters\Cors;

use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;



class LembreteController extends ActiveController{

    public $modelClass = 'common\models\Lembrete';


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
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

    protected function getUserProfileId()
    {
        $user = Yii::$app->user->identity;
        if (!$user || !$user->userprofile) {
            throw new UnauthorizedHttpException('Usuário sem perfil associado');
        }
        return $user->userprofile->id;
    }
    /**
     * GET /lembrete/all
     * Lista todos os lembretes dos animais do cliente
     */
    public function actionAll()
    {
        $permission = Yii::$app->user->can('viewReminders');
        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver lembretes.');
        }

        $userProfileId = $this->getUserProfileId();

        $lembretes = Lembrete::find()
            ->where(['userprofiles_id' => $userProfileId])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($lembretes as $lembrete) {
            $result[] = [
                'id' => $lembrete->id,
                'descricao' => $lembrete->descricao,
                'created_at' => $lembrete->created_at,
                'updated_at' => $lembrete->updated_at,
                'userprofiles_id' => $lembrete->userprofiles_id,
            ];
        }

        return $result;
    }


    public function actionCreate()
    {
        $permission = Yii::$app->user->can('createReminders');

        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para criar lembretes.');
        }
        
        $userProfileId = $this->getUserProfileId();
        $data = Yii::$app->request->post();

        $lembrete = new $this->modelClass();
        $lembrete->attributes = $data;
        $lembrete->userprofiles_id = $userProfileId;

        if ($lembrete->save()) {
            return [
                'success' => true,
                'message' => 'Lembrete criado com sucesso',
                'lembrete' => [
                    'descricao' => $lembrete->descricao,
                ],
            ];
        } else {     
            throw new BadRequestHttpException('Erro ao criar lembrete: ' . json_encode($lembrete->errors));
        }
    }
    /**
     * GET /lembrete/view/{id}
     * Detalhes de um lembrete específico
     */
    public function actionView($id)
    {
        
        $permission = Yii::$app->user->can('viewReminders');

        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para ver lembretes.');
        }

        $userProfileId = $this->getUserProfileId();

        $lembrete = Lembrete::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId])
            ->one();

        if (!$lembrete) {
            throw new NotFoundHttpException('Lembrete não encontrado');
        }

        return [
            'id' => $lembrete->id,
            'descricao' => $lembrete->descricao,
            'created_at' => $lembrete->created_at,
            'updated_at' => $lembrete->updated_at,
            'userprofiles_id' => $lembrete->userprofiles_id,
        ];
    }
    /**
     * PUT /lembrete/update/{id}
     * Atualizar um lembrete existente
     */
    public function actionUpdate($id)
    {
        $permission = Yii::$app->user->can('updateReminders');

        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para atualizar lembretes.');
        }

        // Verifica se o lembrete pertence ao usuário autenticado
        $userProfileId = $this->getUserProfileId();

        $lembrete = Lembrete::findOne(['id' => $id, 'userprofiles_id' => $userProfileId]);
        if (!$lembrete) {
            throw new NotFoundHttpException('Lembrete não encontrado');
        }

        $data = Yii::$app->request->post();
        $textoLembrete = $data['descricao'] ?? null;

        if (!$textoLembrete) {
            throw new BadRequestHttpException('Texto do lembrete é obrigatório');
        }

        // Atualiza a descrição do lembrete
        $lembrete->descricao = $textoLembrete;

        if (!$lembrete->save()) {
            throw new BadRequestHttpException('Erro ao atualizar lembrete: ' . json_encode($lembrete->errors));
        }

        return [
            'success' => true,
            'message' => 'Lembrete atualizado com sucesso',
            'lembrete' => [
                'id' => $lembrete->id,
                'descricao' => $lembrete->descricao,
            ],
        ];
    }

    /**
     * DELETE /lembrete/delete/{id}
     * Deletar um lembrete existente
     */
    public function actionDelete($id)
    {
        $permission = Yii::$app->user->can('deleteReminders');

        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para deletar lembretes.');
        }

        $userProfileId = $this->getUserProfileId();

        $lembrete = Lembrete::findOne(['id' => $id, 'userprofiles_id' => $userProfileId]);
        if (!$lembrete) {
            throw new NotFoundHttpException('Lembrete não encontrado');
        }

        // Delete real
        if (!$lembrete->delete()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao deletar lembrete');
        }

        return [
            'success' => true,
            'message' => 'Lembrete deletado com sucesso',
        ];
    }

}
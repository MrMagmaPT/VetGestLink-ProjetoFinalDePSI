<?php
namespace frontend\controllers;

use Yii;
use common\models\Morada;
use backend\models\MoradaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * Controller responsável pela gestão de moradas do perfil do utilizador.
 */
class MoradaController extends Controller
{
    /**
     * Comportamentos do controller (acesso e verbos HTTP).
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['createAddresses', 'updateAddresses', 'deleteAddresses'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'remove' => ['POST'],
                    ],
                ],
            ]
        );  
    }

    /**
     * Criar uma nova morada.
     */
    public function actionCreate()
    {
        // Obtém o perfil do usuário logado
        $user = Yii::$app->user->identity;

        // Verifica se o perfil existe
        $profile = $user->userprofile ?? null;
        if (!$profile) {
            Yii::$app->session->setFlash('error', 'Perfil não encontrado.');
            return $this->redirect(['/userprofile/update']);
        }

        // Cria uma nova instância de Morada
        $model = new Morada();
        // Define o ID do perfil do usuário na morada
        $model->userprofiles_id = $profile->id;
        // Define como morada secundária por padrão
        $model->principal = 0;

        // Processa o formulário de criação
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Morada adicionada com sucesso.');
            return $this->redirect(['/userprofile/update']);
        }

        // Renderiza o formulário de criação
        return $this->render('/userprofile/_add_morada', ['model' => $model, 'profileId' => $profile->id]);
    }

    /**
     * Atualizar uma morada existente.
     */
    public function actionUpdate($id)
    {
        // Obtém o perfil do usuário logado
        $user = Yii::$app->user->identity;

        // Verifica se o perfil existe
        $profile = $user->userprofile;
        if (!$profile) {
            Yii::$app->session->setFlash('error', 'Perfil não encontrado.');
            return $this->redirect(['/userprofile/update']);
        }

        // Encontra a morada a ser atualizada
        $moradaAtualizada = $this->findModel($id);
        if ($moradaAtualizada->userprofiles_id != $profile->id) {
            Yii::$app->session->setFlash('error', 'Morada não encontrada.');
            return $this->redirect(['/userprofile/update']);
        }

        // Processa o formulário de atualização
        if ($moradaAtualizada->load(Yii::$app->request->post()) && $moradaAtualizada->save()) {
            Yii::$app->session->setFlash('success', 'Morada atualizada com sucesso.');
            return $this->redirect(['/userprofile/update']);
        }

        // Renderiza o formulário de atualização
        return $this->render('/userprofile/_add_morada', ['model' => $moradaAtualizada, 'profileId' => $profile->id]);
    }


    /**
     * Remover uma morada permanentemente.
     * Suporta requisições PJAX/AJAX (retorna partial) e fallback redirect.
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        $profile = $user->userprofile;

        if (!$profile) {
            if (Yii::$app->request->isPjax || Yii::$app->request->isAjax) {
                return $this->asJson(['error' => 'Perfil não encontrado.']);
            }
            Yii::$app->session->setFlash('error', 'Perfil não encontrado.');
            return $this->redirect(['/userprofile/update']);
        }


        $morada = $this->findModel($id);
        if ($morada->userprofiles_id != $profile->id) {
            if (Yii::$app->request->isPjax || Yii::$app->request->isAjax) {
                return $this->asJson(['error' => 'Morada não encontrada ou sem permissão.']);
            }
            Yii::$app->session->setFlash('error', 'Morada não encontrada ou sem permissão.');
            return $this->redirect(['/userprofile/update']);
        }

        $morada->delete();

        // Lista atualizada de moradas
        $moradas = $profile->getMoradas()->all();
        
        // Resposta para PJAX/AJAX
        if (Yii::$app->request->isPjax || Yii::$app->request->isAjax) {
            // Renderiza alerta + lista
            return Yii::$app->session->setFlash('success', 'Morada removida com sucesso.') . $this->renderAjax('/userprofile/moradas_list', ['moradas' => $moradas]);
        }
        
        return $this->redirect(['/userprofile/update']);
    }

    /**
     * Finds the Morada model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Morada the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Morada::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

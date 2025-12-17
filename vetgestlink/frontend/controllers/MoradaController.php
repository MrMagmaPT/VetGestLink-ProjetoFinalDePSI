<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Morada;

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
        $user = Yii::$app->user->identity;
        $profile = $user->userprofile;

        if (!$profile || empty($profile->id)) {
            Yii::$app->session->setFlash('warning', 'Por favor, salve o perfil antes de adicionar moradas.');
            return $this->redirect(['/userprofile/update']);
        }

        $model = new Morada();
        $model->userprofiles_id = $profile->id;
        $model->principal = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Morada adicionada com sucesso.');
            return $this->redirect(['/userprofile/update']);
        }

        return $this->render('/userprofile/_add_morada', ['model' => $model, 'profileId' => $profile->id]);
    }

    /**
     * Atualizar uma morada existente.
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $profile = $user->userprofile;

        if (!$profile) {
            Yii::$app->session->setFlash('error', 'Perfil não encontrado.');
            return $this->redirect(['/userprofile/update']);
        }

        $model = Morada::findOne($id);
        if (!$model || $model->userprofiles_id != $profile->id) {
            Yii::$app->session->setFlash('error', 'Morada não encontrada ou sem permissão.');
            return $this->redirect(['/userprofile/update']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Morada atualizada com sucesso.');
            return $this->redirect(['/userprofile/update']);
        }

        return $this->render('/userprofile/_add_morada', ['model' => $model, 'profileId' => $profile->id]);
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

        $morada = Morada::findOne($id);
        if (!$morada || $morada->userprofiles_id != $profile->id) {
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

}

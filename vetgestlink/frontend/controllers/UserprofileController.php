<?php

namespace frontend\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Userprofile;
use common\models\Morada;

/**
 * UserprofileController implements the CRUD actions for Userprofile model.
 */
class UserprofileController extends Controller
{
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
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Displays the user profile view.
     *
     * @param int|null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id = null)
    {
        $id = $id ?: Yii::$app->user->id;

        // Eager load userprofile e moradas (ajuste aqui se o nome da relação for diferente)
        $user = User::find()->where(['id' => $id])->with(['userprofile.moradas'])->one();

        if (!$user) {
            throw new NotFoundHttpException('Utilizador não encontrado.');
        }

        // garante que temos um Userprofile (pode ser null)
        $profile = $user->userprofile;
        if ($profile === null) {
            $profile = new Userprofile();
            $profile->user_id = $user->id;
            $moradas = [];
        } else {
            // tenta obter moradas por nomes comuns de relação
            $moradas = [];
            // acesso direto: se a relação existir isto traz as moradas (lazy ou eager)
            if (isset($profile->moradas) && is_array($profile->moradas) && count($profile->moradas)) {
                $moradas = $profile->moradas;
            } elseif (isset($profile->enderecos) && is_array($profile->enderecos) && count($profile->enderecos)) {
                // alternativa: 'enderecos' / 'endereços'
                $moradas = $profile->enderecos;
            } elseif (isset($profile->address) && is_array($profile->address) && count($profile->address)) {
                $moradas = $profile->address;
            } else {
                $moradas = [];
            }
        }

        return $this->render('view', [
            'user' => $user,
            'model' => $profile,
            'moradas' => $moradas,
        ]);
    }

    /**
     * Updates the user profile.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */

    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;
        $moradas = $model->moradas;

        if ($this->request->isPost && $model->load($this->request->post())) {
            Model::loadMultiple($moradas, $this->request->post());

            if ($model->save(false)) {
                foreach ($moradas as $morada) {
                    $morada->save(false);
                }

                Yii::$app->session->setFlash('success', 'Perfil atualizado com sucesso.');
                return $this->redirect(['view']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'moradas' => $moradas,
        ]);
    }



    /**
     * Saves the user profile (POST action).
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSave()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;
        if ($model === null) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }
        $moradas = $model->moradas ?? [];

        if ($this->request->isPost && $model->load($this->request->post())) {
            Model::loadMultiple($moradas, $this->request->post());

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save(false)) {
                    throw new \RuntimeException('Erro ao salvar perfil.');
                }
                foreach ($moradas as $morada) {
                    if (!$morada->save(false)) {
                        throw new \RuntimeException('Erro ao salvar morada.');
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Perfil editado com sucesso.');
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'Erro ao editar o perfil.');
            }
        }

        // redirecionar para a view do utilizador atual explicitamente
        return $this->redirect(['view', 'id' => $user->id ?? null]);
    }

    /**
     * Finds the Userprofile model based on its primary key value.
     *
     * @param int $id
     * @return Userprofile
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Userprofile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

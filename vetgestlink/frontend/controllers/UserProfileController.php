<?php

namespace frontend\controllers;

use yii\base\Model;
use common\models\userprofile;
use common\models\Morada;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserprofileController implements the CRUD actions for userprofile model.
 */
class UserprofileController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
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
     * @return string
     */
    public function actionView()
    {
        $user = Yii::$app->user->identity;

        $user = \common\models\User::find()
            ->where(['id' => $user->id])
            ->with(['userprofile.moradas'])
            ->one();

        $model = $user->userprofile;
        $moradas = $model->moradas ?? [];

        return $this->render('view', [
            'user' => $user,
            'model' => $model,
            'moradas' => $moradas,
        ]);
    }

    /**
     * Alias for view action to maintain compatibility.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->actionView();
    }

    /**
     * Updates the user profile.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
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
     */
    public function actionSave()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;
        $moradas = $model->moradas;

        if ($this->request->isPost && $model->load($this->request->post())) {
            Model::loadMultiple($moradas, $this->request->post());

            $model->save(false);

            foreach ($moradas as $morada) {
                $morada->save(false);
            }

            Yii::$app->session->setFlash('success', 'Perfil editado com sucesso.');
        }

        return $this->redirect(['view']);
    }

    /**
     * Finds the userprofile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return userprofile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = userprofile::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace frontend\controllers;

use yii\base\Model;
use common\models\Userprofile;
use common\models\Morada;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserProfileController implements the CRUD actions for Userprofile model.
 */
class UserProfileController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists information from Userprofile,User and Morada models to the index page .
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        $user = \common\models\User::find()
            ->where(['id' => $user->id])
            ->with(['userProfile.moradas'])
            ->one();

        $userProfile = $user->userProfile;
        $moradas = $userProfile->moradas ?? [];

        return $this->render('index', [
            'user' => $user,
            'userProfile' => $userProfile,
            'moradas' => $moradas,
        ]);
    }

    /**
     * Go to page edit and loads information to the form.
     *
     * @return string
     */
    public function actionEdit()
    {
        $user = Yii::$app->user->identity;
        $userProfile = $user->userProfile;
        $moradas = $userProfile->moradas ?? new \common\models\Morada();

        return $this->render('edit', [
            'user' => $user,
            'userProfile' => $userProfile,
            'moradas' => $moradas,
        ]);
    }



    /**
     * Updates an existing Userprofile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $userProfile = $user->userProfile;
        $moradas = $userProfile->moradas;

        if (
            $this->request->isPost &&
            $userProfile->load($this->request->post())
        ) {
            Model::loadMultiple($moradas, $this->request->post());

            $userProfile->save(false);

            foreach ($moradas as $morada) {
                $morada->save(false);
            }

            Yii::$app->session->setFlash('success', 'Perfil editado com sucesso.');
        }

        return $this->redirect(['index']);
    }






    /**
     * Finds the Userprofile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Userprofile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Userprofile::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

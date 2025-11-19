<?php

namespace backend\controllers;

use yii\base\Model;
use common\models\Userprofile;
use backend\models\UserprofileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\SignupFormBackend;
use Yii;

/**
 * UserprofileController implements the CRUD actions for Userprofile model.
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
     * Lists all Userprofile models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserprofileSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userprofile model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Userprofile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SignupFormBackend();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', 'Utilizador criado com sucesso.');
                return $this->refresh(); // mantém na mesma página para mostrar o flash
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Userprofile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id = null)
    {
        // se não vier id, usa o perfil do utilizador logado
        if ($id === null) {
            $user = Yii::$app->user->identity;
            if (!$user || !$user->userprofile) {
                throw new NotFoundHttpException('Perfil não encontrado.');
            }
            $model = $user->userprofile;
        } else {
            $model = $this->findModel($id);
        }

        $moradas = $model->moradas ?: [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                Model::loadMultiple($moradas, $this->request->post());

                $valid = $model->validate();
                $valid = Model::validateMultiple($moradas) && $valid;

                if ($valid && $model->save()) {
                    foreach ($moradas as $morada) {
                        $morada->save();
                    }

                    Yii::$app->session->setFlash('success', 'Perfil atualizado com sucesso.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'moradas' => $moradas,
        ]);
    }

    /**
     * Deletes an existing Userprofile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Saves the user profile (POST action).
     *
     * @return \yii\web\Response
     */
    public function actionSave($id = null)
    {
        // se vier id, carrega esse perfil; senão usa o do user logado
        if ($id !== null) {
            $model = $this->findModel($id);
        } else {
            $user = Yii::$app->user->identity;
            if (!$user || !$user->userprofile) {
                throw new NotFoundHttpException('Perfil não encontrado.');
            }
            $model = $user->userprofile;
        }

        $moradas = $model->moradas ?: [];

        if ($this->request->isPost && $model->load($this->request->post())) {
            Model::loadMultiple($moradas, $this->request->post());

            $valid = $model->validate();
            $valid = \yii\base\Model::validateMultiple($moradas) && $valid;

            if ($valid) {
                $model->save(false);
                foreach ($moradas as $morada) {
                    $morada->save(false);
                }
                Yii::$app->session->setFlash('success', 'Perfil editado com sucesso.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Corrija os erros assinalados.');
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->redirect(['update', 'id' => $model->id]);
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

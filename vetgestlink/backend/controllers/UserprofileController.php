<?php

namespace backend\controllers;

use yii\base\Model;
use common\models\Userprofile;
use backend\models\UserprofileSearch;
use yii\web\Controller;
use common\models\Morada;
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
                // sucesso
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

    public function actionUpdate($id)
    {
        $userprofile = Userprofile::findOne($id);

        if (!$userprofile) {
            throw new NotFoundHttpException('O perfil solicitado não existe.');
        }

        // Carregar o user relacionado
        $user = $userprofile->user;

        // Carregar a morada principal do userprofile
        $morada = Morada::find()
            ->where(['userprofiles_id' => $userprofile->id, 'principal' => 1])
            ->one();

        if (!$morada) {
            $morada = new Morada();
            $morada->userprofile_id = $userprofile->id;
            $morada->principal = 1;
        }

        $model = new SignupFormBackend();

        // Preencher o modelo do formulário
        $model->username = $user->username;
        $model->email = $user->email;
        $model->nomecompleto = $userprofile->nomecompleto;
        $model->dtanascimento = $userprofile->dtanascimento;
        $model->nif = $userprofile->nif;
        $model->telemovel = $userprofile->telemovel;

        // Dados da morada
        $model->rua = $morada->rua;
        $model->nporta = $morada->nporta;
        $model->andar = $morada->andar;
        $model->cdpostal = $morada->cdpostal;
        $model->cxpostal = $morada->cxpostal;
        $model->localidade = $morada->localidade;
        $model->cidade = $morada->cidade;
        $model->principal = $morada->principal;

        // Obter a role do utilizador
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($user->id);
        $model->role = !empty($roles) ? array_keys($roles)[0] : '';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Atualizar User
                $user->username = $model->username;
                $user->email = $model->email;
                if (!empty($model->password)) {
                    $user->setPassword($model->password);
                }
                $user->save();

                // Atualizar Userprofile
                $userprofile->nomecompleto = $model->nomecompleto;
                $userprofile->dtanascimento = $model->dtanascimento;
                $userprofile->nif = $model->nif;
                $userprofile->telemovel = $model->telemovel;
                $userprofile->save();

                // Atualizar Morada
                $morada->rua = $model->rua;
                $morada->nporta = $model->nporta;
                $morada->andar = $model->andar;
                $morada->cdpostal = $model->cdpostal;
                $morada->cxpostal = $model->cxpostal;
                $morada->localidade = $model->localidade;
                $morada->cidade = $model->cidade;
                $morada->principal = $model->principal;
                $morada->save();

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Utilizador atualizado com sucesso.');
                return $this->redirect(['view', 'id' => $userprofile->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Erro ao atualizar: ' . $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userprofile' => $userprofile
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

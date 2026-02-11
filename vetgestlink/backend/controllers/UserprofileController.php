<?php

namespace backend\controllers;

use Yii;
use yii\base\Model;
use common\models\Userprofile;
use backend\models\UserprofileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use backend\models\SignupFormBackend;

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
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['update', 'save', 'remove-image'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'create', 'delete'],
                            'allow' => true,
                            'roles' => ['admin', 'rececionista'],
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
     * Lists all Userprofile models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserprofileSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Se não for admin, mostrar apenas clientes
        if (!Yii::$app->user->can('admin')) {
            $dataProvider->query
                ->joinWith('user')
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
                ->andWhere(['auth_assignment.item_name' => 'cliente']);
        }

        // Estatísticas para a view
        $totalCount = UserprofileSearch::getTotalCount();
        $activeCount = UserprofileSearch::getActiveCount();
        $deletedCount = UserprofileSearch::getDeletedCount();
        $recentCount = UserprofileSearch::getRecentCount();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'deletedCount' => $deletedCount,
            'recentCount' => $recentCount,
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
                return $this->redirect(['view', 'id' => $user->userprofile->id]);
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

        // Carrega as moradas associadas
        $moradas = $model->moradas ?: [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Processa a role se for admin
                if (Yii::$app->user->can('admin')) {
                    $rolePost = $this->request->post('role');
                    if ($rolePost) {
                        $auth = Yii::$app->authManager;
                        // Remover roles antigas
                        $auth->revokeAll($model->user_id);
                        // Atribuir nova role
                        $role = $auth->getRole($rolePost);
                        if ($role) {
                            $auth->assign($role, $model->user_id);
                        }
                    }
                }
                
                // Carrega os dados das moradas
                Model::loadMultiple($moradas, $this->request->post());

                // Definir morada principal
                $principalIndex = $this->request->post('morada_principal');
                foreach ($moradas as $i => $morada) {
                    $morada->principal = ($principalIndex !== null && $principalIndex == $i) ? 1 : 0;
                }

                // Processa upload de imagem
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile && $model->imageFile->tempName && file_exists($model->imageFile->tempName)) {
                    // uploadImage() já atualiza o atributo $model->foto internamente
                    $model->uploadImage();
                }

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
        // Soft delete: marcar como eliminado
        $model = $this->findModel($id);
        $model->eliminado = 1;
        $model->save(false);

        Yii::$app->session->setFlash('success', 'Perfil marcado como eliminado.');
        return $this->redirect(['index']);
    }

    /**
     * Remove a imagem do perfil do utilizador.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRemoveImage($id)
    {
        $model = $this->findModel($id);
        if ($model->foto && file_exists(Yii::getAlias('@uploads/') . $model->foto)) {
            @unlink(Yii::getAlias('@uploads/') . $model->foto);
        }
        $model->foto = null;
        $model->save(false);
        Yii::$app->session->setFlash('success', 'Imagem de perfil removida com sucesso.');
        return $this->redirect(['update', 'id' => $model->id]);
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
            $valid = Model::validateMultiple($moradas) && $valid;

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

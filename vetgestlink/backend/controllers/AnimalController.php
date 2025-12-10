<?php

namespace backend\controllers;

use Yii;
use common\models\Animal;
use backend\models\AnimalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * AnimalController implements the CRUD actions for Animal model.
 */
class AnimalController extends Controller
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
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['viewAnimals'],
                        ],
                        [
                            'actions' => ['view'],
                            'allow' => true,
                            'roles' => ['viewAnimals'],
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createAnimal'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateAnimal'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteAnimal'],
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
     * Lists all Animal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AnimalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Estatísticas
        $totalCount = Animal::find()->where(['eliminado' => 0])->count();
        $machoCount = Animal::find()->where(['eliminado' => 0, 'sexo' => 'M'])->count();
        $femeaCount = Animal::find()->where(['eliminado' => 0, 'sexo' => 'F'])->count();
        $microchipCount = Animal::find()->where(['eliminado' => 0, 'microship' => 1])->count();
        
        // Listas para filtros
        $especiesList = \backend\models\EspecieSearch::getActiveList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCount' => $totalCount,
            'machoCount' => $machoCount,
            'femeaCount' => $femeaCount,
            'microchipCount' => $microchipCount,
            'especiesList' => $especiesList,
        ]);
    }

    /**
     * Displays a single Animal model.
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
     * Creates a new Animal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Animal();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->uploadImage();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        // Preparar listas para o formulário
        $especiesList = \backend\models\EspecieSearch::getEspeciesList();
        $racasList = \backend\models\RacaSearch::getRacasList();
        $userprofilesList = \backend\models\UserprofileSearch::getActiveOwnersList();

        return $this->render('create', [
            'model' => $model,
            'especiesList' => $especiesList,
            'racasList' => $racasList,
            'userprofilesList' => $userprofilesList,
        ]);
    }

    /**
     * Updates an existing Animal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile) {
                $model->deleteImage(); // remove imagem antiga
                $model->uploadImage();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        // Preparar listas para o formulário
        $especiesList = \backend\models\EspecieSearch::getEspeciesList();
        $racasList = \backend\models\RacaSearch::getRacasList();
        $userprofilesList = \backend\models\UserprofileSearch::getActiveOwnersList();

        return $this->render('update', [
            'model' => $model,
            'especiesList' => $especiesList,
            'racasList' => $racasList,
            'userprofilesList' => $userprofilesList,
        ]);
    }

    /**
     * Deletes an existing Animal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->eliminado = 1;
        $model->save(false);

        Yii::$app->session->setFlash('success', 'Animal marcado como eliminado.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Animal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Animal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Animal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

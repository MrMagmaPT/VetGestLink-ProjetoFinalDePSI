<?php

namespace backend\controllers;
use yii;
use common\models\Medicamento;
use backend\models\MedicamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MedicamentoController implements the CRUD actions for Medicamento model.
 */
class MedicamentoController extends Controller
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
                            'roles' => ['viewMedications'],
                        ],
                        [
                            'actions' => ['view'],
                            'allow' => true,
                            'roles' => ['viewMedications'],
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createMedication'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateMedication'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteMedication'],
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
     * Lists all Medicamento models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MedicamentoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Estatísticas
        $totalCount = Medicamento::find()->where(['eliminado' => 0])->count();
        $stockCritico = Medicamento::find()
            ->where(['<', 'quantidade', 5])
            ->andWhere(['eliminado' => 0])
            ->count();
        $stockBaixo = Medicamento::find()
            ->where(['between', 'quantidade', 5, 9])
            ->andWhere(['eliminado' => 0])
            ->count();
        $stockBom = Medicamento::find()
            ->where(['>', 'quantidade', 9])
            ->andWhere(['eliminado' => 0])
            ->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCount' => $totalCount,
            'stockCritico' => $stockCritico,
            'stockBaixo' => $stockBaixo,
            'stockBom' => $stockBom,
        ]);
    }

    /**
     * Displays a single Medicamento model.
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
     * Creates a new Medicamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Medicamento();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $searchModel = new MedicamentoSearch();
        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Updates an existing Medicamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new MedicamentoSearch();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Deletes an existing Medicamento model.
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

        Yii::$app->session->setFlash('success', 'Medicamento marcado como eliminado.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Medicamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Medicamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Medicamento::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

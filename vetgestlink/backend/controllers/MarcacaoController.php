<?php

namespace backend\controllers;

use common\models\Marcacao;
use backend\models\MarcacaoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * MarcacaoController implements the CRUD actions for Marcacao model.
 */
class MarcacaoController extends Controller
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
                            'roles' => ['viewAppointments', 'viewConsultations'],
                        ],
                        [
                            'actions' => ['view'],
                            'allow' => true,
                            'roles' => ['viewAppointments', 'viewConsultations'],
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createAppointment', 'createConsultation'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateAppointment', 'updateConsultation'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteAppointment', 'deleteConsultation'],
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
     * Lists all Marcacao models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MarcacaoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Estatísticas para a view
        $totalCount = $dataProvider->getTotalCount();
        $pendenteCount = Marcacao::find()->where(['estado' => Marcacao::ESTADO_PENDENTE, 'eliminado' => 0])->count();
        $realizadaCount = Marcacao::find()->where(['estado' => Marcacao::ESTADO_REALIZADA, 'eliminado' => 0])->count();
        $canceladaCount = Marcacao::find()->where(['estado' => Marcacao::ESTADO_CANCELADA, 'eliminado' => 0])->count();
        
        // Listas para filtros
        $animaisList = MarcacaoSearch::getAnimaisList();
        $userprofilesList = MarcacaoSearch::getUserprofilesList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCount' => $totalCount,
            'pendenteCount' => $pendenteCount,
            'realizadaCount' => $realizadaCount,
            'canceladaCount' => $canceladaCount,
            'animaisList' => $animaisList,
            'userprofilesList' => $userprofilesList,
        ]);
    }

    /**
     * Displays a single Marcacao model.
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
     * Creates a new Marcacao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Marcacao();
        
        // Listas para dropdowns
        $animaisList = \backend\models\AnimalSearch::getActiveList();
        $veterinariosList = \backend\models\UserprofileSearch::getUserListByType('veterinario', 0);
        $veterinariosArray = \yii\helpers\ArrayHelper::map($veterinariosList, 'id', 'nomecompleto');
        $medicamentos = \backend\models\MedicamentoSearch::getMedicamentoList();
        $servicosList = \backend\models\ServicoSearch::getActiveList();

        if ($this->request->isPost) {
            //dd($this->request->post());
            if ($model->load($this->request->post())){
                $model->estado = Marcacao::ESTADO_PENDENTE;
                if($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    Yii::error($model->errors, 'marcacao');
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        
        return $this->render('create', [
            'model' => $model,
            'animaisList' => $animaisList,
            'veterinariosArray' => $veterinariosArray,
            'medicamentos' => $medicamentos,
            'servicosList' => $servicosList,
        ]);
    }

    /**
     * Updates an existing Marcacao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Listas para dropdowns
        $animaisList = \backend\models\AnimalSearch::getActiveList();
        $veterinariosList = \backend\models\UserprofileSearch::getUserListByType('veterinario', 0);
        $veterinariosArray = \yii\helpers\ArrayHelper::map($veterinariosList, 'id', 'nomecompleto');
        $servicosList = \backend\models\ServicoSearch::getActiveList();
        
        // Buscar medicamentos disponíveis
        $medicamentos = \backend\models\MedicamentoSearch::getMedicamentoList();

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                // Processar medicamentos selecionados
                $medicamentosSelecionados = $this->request->post('medicamentos', []);

                foreach ($medicamentosSelecionados as $medicamentoId => $dados) {
                    if (isset($dados['quantidade']) && $dados['quantidade'] > 0) {
                        // Atualizar relação MarcacaoMedicamento
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'medicamentos' => $medicamentos,
            'animaisList' => $animaisList,
            'veterinariosArray' => $veterinariosArray,
            'servicosList' => $servicosList,
        ]);
    }


    /**
     * Deletes an existing Marcacao model.
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
     * Finds the Marcacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Marcacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Marcacao::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

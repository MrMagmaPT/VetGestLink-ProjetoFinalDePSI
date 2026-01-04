<?php

namespace backend\controllers;

use common\models\Fatura;
use common\models\Linhafatura;
use backend\models\FaturaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FaturaController implements the CRUD actions for Fatura model.
 */
class FaturaController extends Controller
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
                            'roles' => ['viewInvoices'],
                        ],
                        [
                            'actions' => ['view'],
                            'allow' => true,
                            'roles' => ['viewInvoices'],
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createInvoice'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateInvoice'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteInvoice'],
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
     * Lists all Fatura models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FaturaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Contadores para cards
        $totalCount = Fatura::find()->count();
        $paidCount = Fatura::find()->where(['estado' => 1, 'eliminado' => 0])->count();
        $pendingCount = Fatura::find()->where(['estado' => 0, 'eliminado' => 0])->count();

        // Listas para Select2
        $faturasList = FaturaSearch::getFaturasListForIndex();
        $metodosPagamentoList = FaturaSearch::getMetodosPagamentoListForIndex();
        $estadosList = FaturaSearch::getEstadosListForIndex();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCount' => $totalCount,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'faturasList' => $faturasList,
            'metodosPagamentoList' => $metodosPagamentoList,
            'estadosList' => $estadosList,
        ]);
    }

    /**
     * Displays a single Fatura model.
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
     * Creates a new Fatura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int|null $marcacao_id ID da marcação para criar fatura automaticamente
     * @return string|\yii\web\Response
     */
    public function actionCreate($marcacao_id = null)
    {
        $model = new Fatura();
        $metodosPagamento = FaturaSearch::getMetodosPagamentoAtivos();
        $userprofilesList = \backend\models\UserprofileSearch::getActiveOwnersList();
        
        // Se vier de uma marcação, preencher dados automaticamente
        if ($marcacao_id) {
            $marcacao = \common\models\Marcacao::findOne($marcacao_id);
            if ($marcacao && $marcacao->estado === \common\models\Marcacao::ESTADO_REALIZADA) {
                $model->userprofiles_id = $marcacao->animais->userprofiles_id ?? null;
                $model->estado = 0; // Pendente
                $model->total = $marcacao->servicos->valor ?? 0;
            }
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Se veio de uma marcação, criar linha de fatura vinculada
                $marcacao_param = $this->request->post('marcacao_id');
                if ($marcacao_param) {
                    $marcacao = \common\models\Marcacao::findOne($marcacao_param);
                    if ($marcacao) {
                        $linha = new Linhafatura();
                        $linha->faturas_id = $model->id;
                        $linha->marcacoes_id = $marcacao->id;
                        $linha->quantidade = 1;
                        $linha->total = $marcacao->servicos->valor ?? 0;
                        $linha->vendidoemconsulta = 1;
                        $linha->save();
                        
                        // Atualizar total da fatura
                        $model->atualizarTotal();
                    }
                } else {
                    // Cria linha vazia para faturas manuais
                    $linha = new Linhafatura();
                    $linha->faturas_id = $model->id;
                    $linha->quantidade = 1;
                    $linha->total = 0;
                    $linha->save();
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'metodosPagamento' => $metodosPagamento,
            'userprofilesList' => $userprofilesList,
            'marcacao_id' => $marcacao_id,
        ]);
    }

    /**
     * Updates an existing Fatura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $metodosPagamento = FaturaSearch::getMetodosPagamentoAtivos();
        $userprofilesList = \backend\models\UserprofileSearch::getActiveOwnersList();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'metodosPagamento' => $metodosPagamento,
            'userprofilesList' => $userprofilesList,
        ]);
    }

    /**
     * Deletes an existing Fatura model (soft delete).
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Soft delete da fatura
        $model->eliminado = 1;
        if ($model->save(false)) {
            // Soft delete de todas as linhas de fatura associadas
            Linhafatura::updateAll(
                ['eliminado' => 1],
                ['faturas_id' => $id, 'eliminado' => 0]
            );
            
            \Yii::$app->session->setFlash('success', 'Fatura eliminada com sucesso!');
        } else {
            \Yii::$app->session->setFlash('error', 'Erro ao eliminar fatura.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Fatura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Fatura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fatura::findOne(['id' => $id, 'eliminado' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

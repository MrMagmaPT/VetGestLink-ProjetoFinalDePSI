<?php

namespace backend\controllers;

use Yii;
use common\models\Linhafatura;
use backend\models\LinhafaturaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LinhafaturaController implements the CRUD actions for Linhafatura model.
 */
class LinhafaturaController extends Controller
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
                            'allow' => true,
                            'roles' => ['rececionista'],
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
     * Lists all Linhafatura models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LinhafaturaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Linhafatura model.
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
     * Creates a new Linhafatura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($fatura_id = null)
    {
        $model = new Linhafatura();
        
        // Se vier com fatura_id, pré-preencher
        if ($fatura_id) {
            $fatura = \common\models\Fatura::findOne($fatura_id);
            if (!$fatura) {
                throw new NotFoundHttpException('Fatura não encontrada.');
            }
            
            // Verificar se a fatura está paga (estado = 1)
            if ($fatura->estado == 1) {
                \Yii::$app->session->setFlash('error', 'Não é possível adicionar linhas a uma fatura já paga.');
                return $this->redirect(['/fatura/view', 'id' => $fatura_id]);
            }
            
            $model->faturas_id = $fatura_id;
        }
        
        // Listas para dropdowns
        $medicamentosList = \backend\models\MedicamentoSearch::getMedicamentoList();
        $servicosList = \backend\models\ServicoSearch::getActiveList();
        $marcacoesList = \backend\models\MarcacaoSearch::getMarcacoesSemFaturaList();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Preparar e validar a linha (calcula total e valida stock)
                if (!$model->prepararParaSalvar()) {
                    // Mostrar os erros de validação
                    foreach ($model->errors as $errors) {
                        foreach ($errors as $error) {
                            \Yii::$app->session->setFlash('error', $error);
                        }
                    }
                    return $this->render('create', [
                        'model' => $model,
                        'medicamentosList' => $medicamentosList,
                        'servicosList' => $servicosList,
                        'marcacoesList' => $marcacoesList,
                    ]);
                }
                
                if ($model->save()) {
                    // Processar stock (decrementar)
                    $model->processarStockAoCriar();
                    
                    // Atualizar total da fatura
                    $fatura = \common\models\Fatura::findOne($model->faturas_id);
                    if ($fatura) {
                        $fatura->atualizarTotal();
                    }
                    
                    \Yii::$app->session->setFlash('success', 'Linha de fatura adicionada com sucesso!');
                    return $this->redirect(['/fatura/view', 'id' => $model->faturas_id]);
                } else {
                    \Yii::$app->session->setFlash('error', 'Erro ao adicionar linha de fatura.');
                }
            }
        } else {
            $model->loadDefaultValues();
            $model->quantidade = 1;
        }

        return $this->render('create', [
            'model' => $model,
            'medicamentosList' => $medicamentosList,
            'servicosList' => $servicosList,
            'marcacoesList' => $marcacoesList,
        ]);
    }

    /**
     * Updates an existing Linhafatura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $faturaId = $model->faturas_id;
        
        // Verificar se a fatura está paga
        $fatura = \common\models\Fatura::findOne($faturaId);
        if ($fatura && $fatura->estado == 1) {
            \Yii::$app->session->setFlash('error', 'Não é possível editar linhas de uma fatura já paga.');
            return $this->redirect(['/fatura/view', 'id' => $faturaId]);
        }
        
        // Guardar quantidade original para ajustar stock
        $quantidadeOriginal = $model->quantidade;
        $medicamentoIdOriginal = $model->medicamentos_id;
        
        // Listas para dropdowns
        $medicamentosList = \backend\models\MedicamentoSearch::getMedicamentoList();
        $servicosList = \backend\models\ServicoSearch::getActiveList();
        $marcacoesList = \backend\models\MarcacaoSearch::getMarcacoesSemFaturaList();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Calcular total automaticamente
                $model->total = $model->calcularTotal();
                
                // Processar stock (validar e ajustar)
                $resultado = $model->processarStockAoAtualizar($quantidadeOriginal, $medicamentoIdOriginal);
                if (!$resultado['success']) {
                    \Yii::$app->session->setFlash('error', $resultado['error']);
                    return $this->render('update', [
                        'model' => $model,
                        'medicamentosList' => $medicamentosList,
                        'servicosList' => $servicosList,
                        'marcacoesList' => $marcacoesList,
                    ]);
                }
                
                if ($model->save()) {
                    // Atualizar total da fatura
                    $fatura = \common\models\Fatura::findOne($model->faturas_id);
                    if ($fatura) {
                        $fatura->atualizarTotal();
                    }
                    
                    \Yii::$app->session->setFlash('success', 'Linha de fatura atualizada com sucesso!');
                    return $this->redirect(['/fatura/view', 'id' => $model->faturas_id]);
                } else {
                    \Yii::$app->session->setFlash('error', 'Erro ao atualizar linha de fatura.');
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'medicamentosList' => $medicamentosList,
            'servicosList' => $servicosList,
            'marcacoesList' => $marcacoesList,
        ]);
    }

    /**
     * Deletes an existing Linhafatura model (soft delete).
     * If deletion is successful, the browser will be redirected to the fatura view page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $faturaId = $model->faturas_id;
        
        // Verificar se a fatura está paga
        $fatura = \common\models\Fatura::findOne($faturaId);
        if ($fatura && $fatura->estado == 1) {
            \Yii::$app->session->setFlash('error', 'Não é possível eliminar linhas de uma fatura já paga.');
            return $this->redirect(['/fatura/view', 'id' => $faturaId]);
        }
        
        // Soft delete
        $model->eliminado = 1;
        if ($model->save(false)) {
            // Devolver stock do medicamento ao eliminar linha
            $model->devolverStock();
            
            // Atualizar total da fatura
            $fatura = \common\models\Fatura::findOne($faturaId);
            if ($fatura) {
                $fatura->atualizarTotal();
            }
            \Yii::$app->session->setFlash('success', 'Linha de fatura eliminada com sucesso!');
        } else {
            \Yii::$app->session->setFlash('error', 'Erro ao eliminar linha de fatura.');
        }

        return $this->redirect(['/fatura/view', 'id' => $faturaId]);
    }

    /**
     * Finds the Linhafatura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Linhafatura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Linhafatura::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

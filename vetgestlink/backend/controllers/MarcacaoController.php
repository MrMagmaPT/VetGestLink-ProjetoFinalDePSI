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
                        [
                            'actions' => ['gerar-fatura'],
                            'allow' => true,
                            'roles' => ['createInvoice'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'gerar-fatura' => ['POST'],
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
        
        // Listas para filtros Select2
        $datasList = MarcacaoSearch::getDatasListForIndex();
        $animaisList = MarcacaoSearch::getAnimaisListForIndex();
        $userprofilesList = MarcacaoSearch::getVeterinariosListForIndex();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCount' => $totalCount,
            'pendenteCount' => $pendenteCount,
            'realizadaCount' => $realizadaCount,
            'canceladaCount' => $canceladaCount,
            'datasList' => $datasList,
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
        $model = $this->findModel($id);
        
        // Get medications used in this appointment
        $medicamentosUtilizados = $model->getLinhasfaturasMedicamentos()->all();
        
        return $this->render('view', [
            'model' => $model,
            'medicamentosUtilizados' => $medicamentosUtilizados,
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
        $medicamentos = \common\models\Medicamento::find()
            ->where(['eliminado' => 0])
            ->orderBy('nome')
            ->all();
        $servicosList = \backend\models\ServicoSearch::getActiveList();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())){
                $model->estado = Marcacao::ESTADO_PENDENTE;
                if($model->save()) {
                    // Criar fatura automaticamente usando o método do modelo
                    $fatura = \common\models\Fatura::criarDeMarcacao($model);
                    
                    // Definir mensagens flash com base no resultado
                    if ($fatura) {
                        Yii::$app->session->setFlash('success', 'Marcação e fatura criadas com sucesso!');
                    } else {
                        Yii::$app->session->setFlash('warning', 'Marcação criada, mas houve um erro ao criar a fatura.');
                    }
                    
                    //
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
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
        $medicamentos = \common\models\Medicamento::find()
            ->where(['eliminado' => 0])
            ->orderBy('nome')
            ->all();
        
        // Get current medications used in this appointment
        $medicamentosAtuais = $model->getLinhasfaturasMedicamentos()->all();
        
        // Create map of current medications for easy lookup
        $medicamentosAtuaisMap = [];
        foreach ($medicamentosAtuais as $linha) {
            $medicamentosAtuaisMap[$linha->medicamentos_id] = [
                'quantidade' => $linha->quantidade,
                'linha_id' => $linha->id,
            ];
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                // Process medications only if appointment is "realizada"
                if ($model->estado === \common\models\Marcacao::ESTADO_REALIZADA) {
                    $medicamentosSelecionados = $this->request->post('medicamentos', []);
                    
                    // Find or create a fatura for this marcacao
                    $linhaFatura = \common\models\Linhafatura::find()
                        ->where(['marcacoes_id' => $model->id, 'eliminado' => 0])
                        ->andWhere(['IS NOT', 'faturas_id', null])
                        ->one();
                    
                    $fatura = null;
                    if ($linhaFatura) {
                        $fatura = \common\models\Fatura::findOne(['id' => $linhaFatura->faturas_id, 'eliminado' => 0]);
                    }
                    
                    if (!$fatura) {
                        // Create a temporary invoice for this appointment
                        $fatura = new \common\models\Fatura();
                        $fatura->total = 0; // Will be updated later
                        $fatura->userprofiles_id = $model->userprofiles_id;
                        $fatura->metodospagamentos_id = 1; // Default payment method, adjust as needed
                        $fatura->estado = 'pendente';
                        
                        if (!$fatura->save()) {
                            Yii::$app->session->setFlash('error', 'Erro ao criar fatura para os medicamentos.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                    
                    // Track which medications are still selected
                    $medicamentosProcessados = [];
                    
                    // Process selected medications
                    foreach ($medicamentosSelecionados as $medicamentoId => $dados) {
                        if (isset($dados['quantidade']) && $dados['quantidade'] > 0) {
                            $quantidade = (int)$dados['quantidade'];
                            $medicamento = \common\models\Medicamento::findOne($medicamentoId);
                            
                            if ($medicamento) {
                                // Check if this medication was already used
                                if (isset($medicamentosAtuaisMap[$medicamentoId])) {
                                    // Update existing line
                                    $linhaExistente = \common\models\Linhafatura::findOne($medicamentosAtuaisMap[$medicamentoId]['linha_id']);
                                    $quantidadeAnterior = $medicamentosAtuaisMap[$medicamentoId]['quantidade'];
                                    $diferenca = $quantidade - $quantidadeAnterior;
                                    
                                    if ($diferenca != 0) {
                                        // Check if there's enough stock for increase
                                        if ($diferenca > 0 && $medicamento->quantidade < $diferenca) {
                                            Yii::$app->session->setFlash('error', "Stock insuficiente para {$medicamento->nome}. Disponível: {$medicamento->quantidade}");
                                            continue;
                                        }
                                        
                                        // Update stock
                                        $medicamento->quantidade -= $diferenca;
                                        $medicamento->save(false);
                                        
                                        // Update linha
                                        $linhaExistente->quantidade = $quantidade;
                                        $linhaExistente->total = $quantidade * $medicamento->preco;
                                        $linhaExistente->save(false);
                                    }
                                    
                                    $medicamentosProcessados[] = $medicamentoId;
                                } else {
                                    // Create new line
                                    // Check stock
                                    if ($medicamento->quantidade < $quantidade) {
                                        Yii::$app->session->setFlash('error', "Stock insuficiente para {$medicamento->nome}. Disponível: {$medicamento->quantidade}");
                                        continue;
                                    }
                                    
                                    $novaLinha = new \common\models\Linhafatura();
                                    $novaLinha->marcacoes_id = $model->id;
                                    $novaLinha->medicamentos_id = $medicamentoId;
                                    $novaLinha->quantidade = $quantidade;
                                    $novaLinha->total = $quantidade * $medicamento->preco;
                                    $novaLinha->vendidoemconsulta = 1;
                                    $novaLinha->faturas_id = $fatura->id;
                                    
                                    if ($novaLinha->save(false)) {
                                        // Decrement stock
                                        $medicamento->quantidade -= $quantidade;
                                        $medicamento->save(false);
                                        
                                        $medicamentosProcessados[] = $medicamentoId;
                                    }
                                }
                            }
                        }
                    }
                    
                    // Remove medications that were unselected
                    foreach ($medicamentosAtuaisMap as $medicamentoId => $info) {
                        if (!in_array($medicamentoId, $medicamentosProcessados)) {
                            // Restore stock
                            $medicamento = \common\models\Medicamento::findOne($medicamentoId);
                            if ($medicamento) {
                                $medicamento->quantidade += $info['quantidade'];
                                $medicamento->save(false);
                            }
                            
                            // Delete linha
                            $linha = \common\models\Linhafatura::findOne($info['linha_id']);
                            if ($linha) {
                                $linha->delete();
                            }
                        }
                    }
                    
                    // Update fatura total amount
                    if (isset($fatura)) {
                        $totalFatura = \common\models\Linhafatura::find()
                            ->where(['faturas_id' => $fatura->id, 'eliminado' => 0])
                            ->sum('total');
                        $fatura->total = $totalFatura ?: 0;
                        $fatura->save(false);
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'medicamentos' => $medicamentos,
            'medicamentosAtuaisMap' => $medicamentosAtuaisMap,
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
        // Soft delete: marcar como eliminado
        $model = $this->findModel($id);
        $model->eliminado = 1;
        $model->save(false);

        Yii::$app->session->setFlash('success', 'Marcacao marcada como eliminada.');
        return $this->redirect(['index']);
    }

    /**
     * Gera uma fatura a partir de uma marcação realizada
     * @param int $id ID da marcação
     * @return \yii\web\Response
     * @throws NotFoundHttpException se a marcação não existir
     */
    public function actionGerarFatura($id)
    {
        $marcacao = $this->findModel($id);
        
        // Verificar se a marcação está realizada
        if ($marcacao->estado !== Marcacao::ESTADO_REALIZADA) {
            Yii::$app->session->setFlash('error', 'Apenas marcações realizadas podem gerar faturas.');
            return $this->redirect(['view', 'id' => $id]);
        }
        
        // Verificar se já existe fatura para esta marcação
        $faturaExistente = \common\models\Linhafatura::find()
            ->where(['marcacoes_id' => $id])
            ->one();
            
        if ($faturaExistente) {
            Yii::$app->session->setFlash('warning', 'Já existe uma fatura para esta marcação.');
            return $this->redirect(['/fatura/view', 'id' => $faturaExistente->faturas_id]);
        }
        
        // Criar nova fatura
        $fatura = new \common\models\Fatura();
        $fatura->userprofiles_id = $marcacao->animais->userprofiles_id ?? null;
        $fatura->estado = 0; // Pendente
        $fatura->total = $marcacao->servicos->valor ?? 0;
        
        if ($fatura->save()) {
            // Criar linha de fatura para o serviço da marcação
            $linha = new \common\models\Linhafatura();
            $linha->faturas_id = $fatura->id;
            $linha->marcacoes_id = $marcacao->id;
            $linha->quantidade = 1;
            $linha->total = $marcacao->servicos->valor ?? 0;
            $linha->vendidoemconsulta = 1;
            
            if ($linha->save()) {
                $fatura->atualizarTotal();
                Yii::$app->session->setFlash('success', 'Fatura gerada com sucesso!');
                return $this->redirect(['/fatura/view', 'id' => $fatura->id]);
            }
        }
        
        Yii::$app->session->setFlash('error', 'Erro ao gerar fatura.');
        return $this->redirect(['view', 'id' => $id]);
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

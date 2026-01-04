<?php

namespace backend\controllers;

use common\models\Linhafatura;
use backend\models\LinhafaturaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                // Calcular total baseado no tipo de linha
                if ($model->medicamentos_id) {
                    $medicamento = \common\models\Medicamento::findOne($model->medicamentos_id);
                    if ($medicamento) {
                        // Verificar stock disponível
                        if ($medicamento->quantidade < $model->quantidade) {
                            \Yii::$app->session->setFlash('error', "Stock insuficiente para {$medicamento->nome}. Disponível: {$medicamento->quantidade}");
                            return $this->render('create', [
                                'model' => $model,
                                'medicamentosList' => $medicamentosList,
                                'servicosList' => $servicosList,
                                'marcacoesList' => $marcacoesList,
                            ]);
                        }
                        $model->total = $medicamento->preco * $model->quantidade;
                    }
                } elseif ($model->servicos_id) {
                    // Se selecionou serviço diretamente (não marcação)
                    $servico = \common\models\Servico::findOne($model->servicos_id);
                    if ($servico) {
                        $model->total = $servico->valor * $model->quantidade;
                        $model->vendidoemconsulta = 0; // Serviço avulso, não em consulta
                    }
                } elseif ($model->marcacoes_id) {
                    $marcacao = \common\models\Marcacao::findOne($model->marcacoes_id);
                    if ($marcacao && $marcacao->servicos) {
                        $model->total = $marcacao->servicos->valor * $model->quantidade;
                    }
                }
                
                if ($model->save()) {
                    // Decrementar stock do medicamento
                    if ($model->medicamentos_id) {
                        $medicamento = \common\models\Medicamento::findOne($model->medicamentos_id);
                        if ($medicamento) {
                            $medicamento->quantidade -= $model->quantidade;
                            $medicamento->save(false);
                        }
                    }
                    
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
                // Calcular total baseado no tipo de linha
                if ($model->medicamentos_id) {
                    $medicamento = \common\models\Medicamento::findOne($model->medicamentos_id);
                    if ($medicamento) {
                        // Se mudou o medicamento ou aumentou a quantidade, verificar stock
                        if ($medicamentoIdOriginal != $model->medicamentos_id) {
                            // Mudou de medicamento - devolver stock do antigo e verificar novo
                            if ($medicamentoIdOriginal) {
                                $medicamentoAntigo = \common\models\Medicamento::findOne($medicamentoIdOriginal);
                                if ($medicamentoAntigo) {
                                    $medicamentoAntigo->quantidade += $quantidadeOriginal;
                                    $medicamentoAntigo->save(false);
                                }
                            }
                            if ($medicamento->quantidade < $model->quantidade) {
                                \Yii::$app->session->setFlash('error', "Stock insuficiente para {$medicamento->nome}. Disponível: {$medicamento->quantidade}");
                                return $this->render('update', [
                                    'model' => $model,
                                    'medicamentosList' => $medicamentosList,
                                    'servicosList' => $servicosList,
                                    'marcacoesList' => $marcacoesList,
                                ]);
                            }
                        } else {
                            // Mesmo medicamento - verificar diferença de quantidade
                            $diferenca = $model->quantidade - $quantidadeOriginal;
                            if ($diferenca > 0 && $medicamento->quantidade < $diferenca) {
                                \Yii::$app->session->setFlash('error', "Stock insuficiente para {$medicamento->nome}. Disponível: {$medicamento->quantidade}");
                                return $this->render('update', [
                                    'model' => $model,
                                    'medicamentosList' => $medicamentosList,
                                    'servicosList' => $servicosList,
                                    'marcacoesList' => $marcacoesList,
                                ]);
                            }
                        }
                        $model->total = $medicamento->preco * $model->quantidade;
                    }
                } elseif ($model->servicos_id) {
                    // Se selecionou serviço diretamente (não marcação)
                    $servico = \common\models\Servico::findOne($model->servicos_id);
                    if ($servico) {
                        $model->total = $servico->valor * $model->quantidade;
                        $model->vendidoemconsulta = 0; // Serviço avulso, não em consulta
                    }
                    // Se tinha medicamento antes e agora mudou para serviço, devolver stock
                    if ($medicamentoIdOriginal) {
                        $medicamentoAntigo = \common\models\Medicamento::findOne($medicamentoIdOriginal);
                        if ($medicamentoAntigo) {
                            $medicamentoAntigo->quantidade += $quantidadeOriginal;
                            $medicamentoAntigo->save(false);
                        }
                    }
                } elseif ($model->marcacoes_id) {
                    $marcacao = \common\models\Marcacao::findOne($model->marcacoes_id);
                    if ($marcacao && $marcacao->servicos) {
                        $model->total = $marcacao->servicos->valor * $model->quantidade;
                    }
                    // Se tinha medicamento antes e agora mudou para marcação, devolver stock
                    if ($medicamentoIdOriginal) {
                        $medicamentoAntigo = \common\models\Medicamento::findOne($medicamentoIdOriginal);
                        if ($medicamentoAntigo) {
                            $medicamentoAntigo->quantidade += $quantidadeOriginal;
                            $medicamentoAntigo->save(false);
                        }
                    }
                }
                
                if ($model->save()) {
                    // Ajustar stock do medicamento
                    if ($model->medicamentos_id) {
                        $medicamento = \common\models\Medicamento::findOne($model->medicamentos_id);
                        if ($medicamento) {
                            if ($medicamentoIdOriginal == $model->medicamentos_id) {
                                // Mesmo medicamento - ajustar pela diferença
                                $diferenca = $model->quantidade - $quantidadeOriginal;
                                $medicamento->quantidade -= $diferenca;
                                $medicamento->save(false);
                            } else {
                                // Medicamento novo - decrementar a quantidade total
                                $medicamento->quantidade -= $model->quantidade;
                                $medicamento->save(false);
                            }
                        }
                    }
                    
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
            if ($model->medicamentos_id) {
                $medicamento = \common\models\Medicamento::findOne($model->medicamentos_id);
                if ($medicamento) {
                    $medicamento->quantidade += $model->quantidade;
                    $medicamento->save(false);
                }
            }
            
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

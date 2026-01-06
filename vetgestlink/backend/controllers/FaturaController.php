<?php

namespace backend\controllers;

use Yii;
use common\models\Fatura;
use common\models\Linhafatura;
use backend\models\FaturaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

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
                        [
                            'actions' => ['pdf'],
                            'allow' => true,
                            'roles' => ['viewInvoices'],
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
        $totalCount = FaturaSearch::getTotalCount();
        $paidCount = FaturaSearch::getPaidCount();
        $pendingCount = FaturaSearch::getPendingCount();

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
                $model->estado = '0'; // Pendente
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
        
        // Verificar se a fatura está paga
        if ($model->estado == '1') {
            \Yii::$app->session->setFlash('error', 'Não é possível editar uma fatura já paga.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
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

    /**
     * Gera um PDF da fatura.
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPdf(string $nome_emissor, string $nome_recep, int $id)
    {
        $model = $this->findModel($id);
        $linhas = $model->linhasfaturas;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        // montar data URI para o logo (opção embutida)
        $logoPath = \Yii::getAlias('@frontend/web/static/img/logo/logo.png');
        $logoDataUri = '';
        if (is_file($logoPath) && is_readable($logoPath)) {
            $mime = mime_content_type($logoPath) ?: 'image/png';
            $logoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
        $content = $this->renderPartial('_pdf', [
            'model' => $model,
            'linhas' => $linhas,
            'nome_emissor' => $nome_emissor,
            'nome_recep' => $nome_recep,
            'logoDataUri' => $logoDataUri,
        ]);
        $cssInline = '
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#222; margin:0; padding:0; }
        .logo { margin-bottom:12px; }
        .logo img { height:60px; }
        .header { display:flex; justify-content:space-between; margin-bottom:20px; }
        .company, .client { width:48%; }
        .invoice-meta { text-align:right; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th, td { border:1px solid #ddd; padding:8px; vertical-align:top; }
        th { background:#f5f5f5; text-align:left; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .total-row td { border:none; padding-top:12px; }
        .total-amount { font-size:1.2em; font-weight:bold; color:#0a8a00; }
        .invoice-meta h3 { margin:0 0 8px 0; }
        h2, h3 { margin:0 0 8px 0; }
        .observacoes { margin-top:30px; font-size:11px; color:#666; }
    ';

        $created = $model->created_at ? date('Ymd_His', strtotime($model->created_at)) : date('Ymd_His');
        $fileTitle = 'Fatura_' . $nome_emissor . '_' . $created;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => $cssInline,
            'options' => [
                'title' => $fileTitle,
                'subject' => 'Fatura gerada pelo sistema VetGestLink',
            ],
            'methods' => [
                'SetTitle' => [$fileTitle],
                'SetHeader' => ['Fatura de Pagamento || Gerada em: ' . \Yii::$app->formatter->asDatetime(time(), 'php:d/m/Y H:i')],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => [$nome_recep],
                'SetCreator' => ['VetGestLink'],
            ],
        ]);

        return $pdf->render();
    }

}

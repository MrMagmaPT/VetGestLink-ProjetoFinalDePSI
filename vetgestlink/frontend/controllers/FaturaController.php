<?php
namespace frontend\controllers;


use Yii;
use common\models\Fatura;
use backend\models\FaturaSearch;
use backend\models\MetodopagamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

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
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index','view'],
                            'allow' => true,
                            'roles' => ['viewInvoices'],
                        ],
                        [
                            'actions' => ['pagar'],
                            'allow' => true,
                            'roles' => ['payInvoices'],
                        ],
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
        // Obtém o ID do usuário logado 
        $userId = Yii::$app->user->identity->id ?? null;
        
        // Buscar o Userprofile do usuário logado
        $userprofile = \common\models\Userprofile::findOne(['user_id' => $userId, 'eliminado' => 0]);
        
        if (!$userprofile) {
            Yii::$app->session->setFlash('error', 'Perfil de usuário não encontrado.');
            return $this->render('index', ['faturasUsuario' => []]);
        }
    
        // Usar o SearchModel do backend para obter faturas do usuário
        $faturasUsuario = FaturaSearch::getByUserId($userprofile->id);
        
        // Renderizar a view com o dataProvider
        return $this->render('index', [
            'faturasUsuario' => $faturasUsuario,
        ]);
    }

    /**
     * Mostra detalhes de uma fatura específica.
     * @param mixed $id
     * @throws Yii\web\NotFoundHttpException
     * @return string
     */
    public function actionView($id)
    {
        // Buscar o modelo da fatura
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Pagar uma fatura.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPagar($id)
    {
        // Encontrar o modelo da fatura com base no ID
        $model = $this->findModel($id);

        // Usar o SearchModel do backend para métodos de pagamento ativos
        $searchModel = new MetodopagamentoSearch();

        // Obter a lista de métodos de pagamento ativos
        $metodosAtivos = $searchModel->getActiveList();

        // Processar o pagamento
        if ($model->load(Yii::$app->request->post())) {
            // Marca como pago
            $model->estado = 1; 
            //e False para pular validação, ajustar conforme necessário
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Pagamento realizado com sucesso!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao salvar o pagamento.');
            }
        }

        return $this->render('pagar', [
            'model' => $model,
            'metodos' => $metodosAtivos, 
        ]);
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
        // Buscar o Userprofile do usuário logado
        $userId = Yii::$app->user->identity->id ?? null;
        $userprofile = \common\models\Userprofile::findOne(['user_id' => $userId, 'eliminado' => 0]);
        
        if (!$userprofile) {
            throw new NotFoundHttpException('Perfil de usuário não encontrado.');
        }
        
        // Buscar a fatura apenas se pertencer ao usuário logado
        if (($model = Fatura::findOne(['id' => $id, 'userprofiles_id' => $userprofile->id, 'eliminado' => 0])) !== null) {
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
        $logoPath = \Yii::getAlias('@frontend/web/static/img/logo/logo_fatura.svg');
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

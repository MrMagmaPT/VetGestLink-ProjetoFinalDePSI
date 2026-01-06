<?php
namespace frontend\controllers;

use Yii;
use common\models\Fatura;
use backend\models\FaturaSearch;
use backend\models\MetodopagamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

}

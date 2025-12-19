<?php
namespace frontend\controllers;

use Yii;
use common\models\Lembrete;
use backend\models\LembreteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * LembreteController implements the CRUD actions for Lembrete model.
 */
class LembreteController extends Controller
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
                            'roles' => ['viewReminders'],
                        ],
                        [
                            'actions' => ['create','update','delete'],
                            'allow' => true,
                            'roles' => ['createReminders','updateReminders','deleteReminders'],
                        ],
                    ],
                ],
            ]
        );
    }
    /**
     * Lists all Lembrete models.
     * @param int $id User ID
     * @return string
     */
    public function actionIndex()
    {
        // Obtém o ID do usuário logado
        $userId = Yii::$app->user->identity->id ?? null;

        // Buscar os lembretes do usuário através do LembreteSearch do backend
        $lembretesUsuario = LembreteSearch::getByUserId($userId);

        // Renderizar a view com os lembretes do usuário
        // E passar o userId para a criação de novos lembretes
        return $this->render('index', [
            'lembretesUsuario' => $lembretesUsuario,
        ]);
    }

    /**
     * Displays a single Lembrete model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Encontrar o modelo Lembrete pelo ID
        $lembrete = $this->findModel($id);
        
        return $this->render('view', [
            'lembrete' => $lembrete,
        ]);
    }

    /**
     * Creates a new Lembrete model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        // Obtém o perfil do usuário logado
        $user = Yii::$app->user->identity;

        // Verifica se o perfil existe
        $profile = $user->userprofile ?? null;

        // Se não existir, lança exceção
        if (!$profile) {
            throw new NotFoundHttpException('Perfil de usuário não encontrado.');
        }

        // Cria um novo lembrete associado ao perfil do usuário
        $novoLembrete = new Lembrete();

        // Define o ID do perfil do usuário no lembrete
        $novoLembrete->userprofiles_id = $profile->id;

        // Processa o formulário de criação
        if ($this->request->isPost) {
            if ($novoLembrete->load($this->request->post()) && $novoLembrete->save()) {
                Yii::$app->session->setFlash('success', 'Lembrete criado com sucesso.');
                return $this->redirect(['view', 'id' => $novoLembrete->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao criar lembrete. Verifique os dados e tente novamente.');
            }
        } else {
            // Define os valores padrão para um novo lembrete
            $novoLembrete->loadDefaultValues();
        }

        return $this->render('create', [
            'lembrete' => $novoLembrete,
        ]);
    }

    /**
     * Updates an existing Lembrete model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $lembrete = $this->findModel($id);

        if ($this->request->isPost && $lembrete->load($this->request->post()) && $lembrete->save()) {
            Yii::$app->session->setFlash('success', 'Lembrete atualizado com sucesso.');
            return $this->redirect(['view', 'id' => $lembrete->id]);
        }

        return $this->render('update', [
            'lembrete' => $lembrete,
        ]);
    }

    /**
     * Deletes an existing Lembrete model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // Deleta o lembrete encontrado pelo ID
        $this->findModel($id)->delete();

        // Redireciona para a lista de lembretes
        return $this->redirect(['index']);
    }

    /**
     * Finds the Lembrete model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Lembrete the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lembrete::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

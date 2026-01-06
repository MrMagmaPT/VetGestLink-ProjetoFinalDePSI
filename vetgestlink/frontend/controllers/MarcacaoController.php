<?php
namespace frontend\controllers;

use Yii;
use common\models\Marcacao;
use backend\models\MarcacaoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                            'roles' => ['viewAppointments'],
                        ],
                    ]
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
        // Obtém o ID do usuário logado
        $userId = Yii::$app->user->identity->id ?? null;
        
        // Buscar o Userprofile do usuário logado
        $userprofile = \common\models\Userprofile::findOne(['user_id' => $userId, 'eliminado' => 0]);
        
        if (!$userprofile) {
            Yii::$app->session->setFlash('error', 'Perfil de usuário não encontrado.');
            return $this->render('index', [
                'marcacoesRealizadas' => [],
                'marcacoesPendentes' => [],
                'eventos' => [],
            ]);
        }

        // Usar o SearchModel do backend para obter marcações dos animais do usuário
        $marcacoesUsuario = MarcacaoSearch::getByUserId($userprofile->id);

        // Obter data atual
        $dataAtual = date('Y-m-d');
        $horaAtual = date('H:i:s');

        // Separar marcações em passadas e futuras
        $marcacoesRealizadas = [];
        $marcacoesPendentes = [];
        
        foreach ($marcacoesUsuario as $marcacao) {
            if ($marcacao->estado === 'pendente') {
                $marcacoesPendentes[] = $marcacao;
            } else {
                $marcacoesRealizadas[] = $marcacao;
            }
        }

        // Montar array de eventos para o calendário (apenas futuras)
        $eventos = [];
        foreach ($marcacoesPendentes as $marcacao) {
            $horario = $marcacao->horainicio . ' - ' . $marcacao->horafim;
            $animal = isset($marcacao->animais) ? $marcacao->animais->nome : 'Marcacao';
            $eventos[] = [
                'title' => $animal . ' (' . $marcacao->horainicio . ' - ' . $marcacao->horafim . ')',
                'start' => $marcacao->data . 'T' . $marcacao->horainicio,
                'end' => $marcacao->data . 'T' . $marcacao->horafim,
                'color' => '#007bff',
                'url' => \yii\helpers\Url::to(['marcacao/view', 'id' => $marcacao->id]),
                'allDay' => false,
            ];
        }

        // Renderizar a view com as marcações separadas
        return $this->render('index', [
            'marcacoesRealizadas' => $marcacoesRealizadas,
            'marcacoesPendentes' => $marcacoesPendentes,
            'eventos' => $eventos,
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
        // Encontrar o modelo Marcacao pelo ID
        $marcacao = $this->findModel($id);

        return $this->render('view', [
            'marcacao' => $marcacao,
        ]);
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
        // Buscar o Userprofile do usuário logado
        $userId = Yii::$app->user->identity->id ?? null;
        $userprofile = \common\models\Userprofile::findOne(['user_id' => $userId, 'eliminado' => 0]);
        
        if (!$userprofile) {
            throw new NotFoundHttpException('Perfil de usuário não encontrado.');
        }
        
        // Buscar marcação e verificar se o animal pertence ao usuário
        $model = Marcacao::find()
            ->where(['id' => $id, 'eliminado' => 0])
            ->one();
            
        if ($model !== null) {
            // Verificar se o animal da marcação pertence ao usuário
            if ($model->animais && $model->animais->userprofiles_id == $userprofile->id) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

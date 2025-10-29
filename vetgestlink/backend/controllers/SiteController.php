<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Userprofiles;
use common\models\Animais;
use common\models\Marcacoes;
use common\models\Faturas;
use common\models\Medicamentos;
use common\models\Categorias;
use common\models\Racas;
use common\models\Especies;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['backendAccess'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $totalClientes = Userprofiles::find()->where(['eliminado' => 0])->count();
        $totalAnimais = Animais::find()->where(['eliminado' => 0])->count();
        $totalMedicamentos = Medicamentos::find()->where(['eliminado' => 0])->count();
        $totalCategorias = Categorias::find()->where(['eliminado' => 0])->count();
        $totalRacas = Racas::find()->where(['eliminado' => 0])->count();
        $totalEspecies = Especies::find()->where(['eliminado' => 0])->count();

        $marcacoesHoje = Marcacoes::find()
            ->where(['DATE(data)' => date('Y-m-d')])
            ->andWhere(['eliminado' => 0])
            ->count();

        $marcacoesPendentes = Marcacoes::find()
            ->where(['estado' => 'Pendente'])
            ->andWhere(['eliminado' => 0])
            ->count();

        $ultimasMarcacoes = Marcacoes::find()
            ->where(['eliminado' => 0])
            ->orderBy(['data' => SORT_DESC])
            ->limit(5)
            ->all();

        $faturasDoMes = Faturas::find()
            ->where(['MONTH(data)' => date('m'), 'YEAR(data)' => date('Y')])
            ->andWhere(['eliminado' => 0])
            ->count();

        $receitaMensal = Faturas::find()
            ->where(['MONTH(data)' => date('m'), 'YEAR(data)' => date('Y')])
            ->andWhere(['eliminado' => 0])
            ->sum('total') ?? 0;

        $userType = $this->getusertype($userId);

        return $this->render('index', [
            'totalClientes' => $totalClientes,
            'totalAnimais' => $totalAnimais,
            'totalMedicamentos' => $totalMedicamentos,
            'totalCategorias' => $totalCategorias,
            'totalRacas' => $totalRacas,
            'totalEspecies' => $totalEspecies,
            'marcacoesHoje' => $marcacoesHoje,
            'marcacoesPendentes' => $marcacoesPendentes,
            'ultimasMarcacoes' => $ultimasMarcacoes,
            'faturasDoMes' => $faturasDoMes,
            'receitaMensal' => $receitaMensal,
            'usertype' => $userType,
        ]);
    }
    private function getusertype($userId) {

        $roles = Yii::$app->authManager->getRolesByUser($userId);
        if (isset($roles['admin'])) {
            return 1;
        }
        if (isset($roles['veterinario'])) {
            return 2;
        }
        if (isset($roles['rececionista'])) {
            return 3;
        };
    }


    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Verifica se tem role permitida
            $auth = Yii::$app->authManager;
            $userId = Yii::$app->user->id;

            /// Verifica se o utilizador tem uma das roles permitidas
            $hasPermission = $auth->checkAccess($userId, 'backendAccess');

            if (!$hasPermission) {
                // Faz logout e mostra erro
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('showFrontendButton', true); // Flag para mostrar botão
                return $this->redirect(['site/login']);
            }

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}

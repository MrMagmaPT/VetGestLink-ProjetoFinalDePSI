<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Marcacoes;
use common\models\Userprofiles;
use common\models\Animais;
use common\models\Faturas;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Contar marcações pendentes (ajuste o campo 'estado' conforme sua tabela)
        $marcacoesPendentes = Marcacoes::find()
            ->where(['estado' => 'Pendente'])
            ->count();

        // Contar total de clientes (userprofiles)
        $totalClientes = Userprofiles::find()->count();

        // Contar total de animais
        $totalAnimais = Animais::find()->count();

        // Contar marcações de hoje
        $marcacoesHoje = Marcacoes::find()
            ->where(['>=', 'data', date('Y-m-d 00:00:00')])
            ->andWhere(['<', 'data', date('Y-m-d 23:59:59')])
            ->count();

        // Total de faturas do mês atual
        $faturasDoMes = Faturas::find()
            ->where(['>=', 'data', date('Y-m-01')])
            ->andWhere(['<', 'data', date('Y-m-t 23:59:59')])
            ->count();

        // Receita total do mês
        $receitaMensal = Faturas::find()
            ->where(['>=', 'data', date('Y-m-01')])
            ->andWhere(['<', 'data', date('Y-m-t 23:59:59')])
            ->sum('total') ?? 0;

        // Últimas 5 marcações
        $ultimasMarcacoes = Marcacoes::find()
            ->with(['animais', 'userprofiles'])
            ->orderBy(['data' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('index', [
            'marcacoesPendentes' => $marcacoesPendentes,
            'totalClientes' => $totalClientes,
            'totalAnimais' => $totalAnimais,
            'marcacoesHoje' => $marcacoesHoje,
            'faturasDoMes' => $faturasDoMes,
            'receitaMensal' => $receitaMensal,
            'ultimasMarcacoes' => $ultimasMarcacoes,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            // Verifica se tem role permitida
            $userId = Yii::$app->user->id;

            if (!Yii::$app->authManager->checkAccess($userId, 'backendAccess')) {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('showFrontendButton', true);
                return $this->redirect(['site/login']);
            }

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

<?php

namespace backend\controllers;

use Yii;
use yii\debug\components\search\matchers\GreaterThan;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Userprofile;
use common\models\Animal;
use common\models\Marcacao;
use common\models\Fatura;
use common\models\Medicamento;
use common\models\Categoria;
use common\models\Raca;
use common\models\Especie;
use function PHPUnit\Framework\lessThan;

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
        $totalClientes = Userprofile::find()->where(['eliminado' => 0])->count();
        $totalAnimais = Animal::find()->where(['eliminado' => 0])->count();

        $totalMedicamentos = Medicamento::find()->where(['eliminado' => 0])->count();

        $totalMedicamentosEmStock = Medicamento::find()
            ->where(['>', 'quantidade', 9])
            ->andWhere(['eliminado' => 0])
            ->count();

        $totalMedicamentosBaixoStock = Medicamento::find()
            ->where(['between', 'quantidade', 5, 9])
            ->andWhere(['eliminado' => 0])
            ->count();

        $totalMedicamentosCriticoStock = Medicamento::find()
            ->where(['<', 'quantidade', 5])
            ->andWhere(['eliminado' => 0])
            ->count();

        $NomeQuantMedicamentosCriticoStock = Medicamento::find()
            ->select(['nome', 'quantidade'])
            ->where(['eliminado' => 0])
            ->andWhere(['<', 'quantidade', 5])
            ->orderBy(['quantidade' => SORT_ASC])
            ->asArray()
            ->all();

        $alertasMedicamentosCriticoStock = array_map(function ($m) {
            return [
                'title' => $m['nome'],
                'content' => 'Quantidade critica: ' . $m['quantidade'],
            ];
        },$NomeQuantMedicamentosCriticoStock);

        $totalCategorias = Categoria::find()->where(['eliminado' => 0])->count();
        $totalRacas = Raca::find()->where(['eliminado' => 0])->count();
        $totalEspecies = Especie::find()->where(['eliminado' => 0])->count();

        $totalMarcacoesHoje = Marcacao::find()
            ->where(['DATE(data)' => date('Y-m-d')])
            ->andWhere(['eliminado' => 0])
            ->count();

        $totalMarcacoesPendentes = Marcacao::find()
            ->where(['estado' => 'Pendente'])
            ->andWhere(['eliminado' => 0])
            ->count();

        $ultimasMarcacoes = Marcacao::find()
            ->where(['eliminado' => 0])
            ->orderBy(['data' => SORT_DESC])
            ->limit(5)
            ->all();

        // Lista de Marcações pendentes
        $marcacoesPendentes = Marcacao::find()
            ->where(['estado' => 'Pendente', 'eliminado' => 0])
            ->asArray()
            ->distinct()
            ->all();

        // Calcula o início e fim do mês atual em UNIX timestamp
        $inicio = strtotime(date('Y-m-01 00:00:00')); // primeiro dia do mês, meia-noite
        $fim = strtotime(date('Y-m-t 23:59:59'));     // último dia do mês, 23:59:59

        // Contagem de faturas do mês
        $faturasDoMes = Fatura::find()
            ->where(['between', 'created_at', $inicio, $fim])
            ->andWhere(['eliminado' => 0])
            ->count();

        // Receita mensal
        $receitaMensal = Fatura::find()
            ->where(['between', 'created_at', $inicio, $fim])
            ->andWhere(['eliminado' => 0])
            ->sum('total') ?? 0;
        $userType = $this->getusertype($userId);

        return $this->render('index', [
            'totalClientes' => $totalClientes,
            'totalAnimais' => $totalAnimais,
            'totalMedicamentos' => $totalMedicamentos,
            'totalMedicamentosEmStock' => $totalMedicamentosEmStock,
            'totalMedicamentosBaixoStock' => $totalMedicamentosBaixoStock,
            'totalMedicamentosCriticoStock' => $totalMedicamentosCriticoStock,
            'alertasMedicamentosCriticoStock' => $alertasMedicamentosCriticoStock,
            'totalCategorias' => $totalCategorias,
            'totalRacas' => $totalRacas,
            'totalEspecies' => $totalEspecies,
            'totalmarcacoesHoje' => $totalMarcacoesHoje,
            'totalmarcacoesPendentes' => $totalMarcacoesPendentes,
            'ultimasMarcacoes' => $ultimasMarcacoes,
            'faturasDoMes' => $faturasDoMes,
            'receitaMensal' => $receitaMensal,
            'usertype' => $userType,
            'marcacoesPendentes' => $marcacoesPendentes,
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

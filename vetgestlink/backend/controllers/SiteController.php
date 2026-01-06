<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Userprofile;
use common\models\Medicamento;
use common\models\Categoria;
use backend\models\AnimalSearch;
use backend\models\MarcacaoSearch;
use backend\models\FaturaSearch;
use backend\models\UserprofileSearch;
use backend\models\RacaSearch;
use backend\models\EspecieSearch;

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
        $userType = $this->getusertype($userId);

        // Estatísticas de Clientes e Animais
        $totalClientes = UserprofileSearch::getActiveCount();
        $totalAnimais = AnimalSearch::getTotalCount();

        // Estatísticas de Medicamentos
        $stockStats = Medicamento::getStockStats();
        $totalMedicamentos = $stockStats['total'];
        $totalMedicamentosEmStock = $stockStats['emStock'];
        $totalMedicamentosBaixoStock = $stockStats['baixoStock'];
        $totalMedicamentosCriticoStock = $stockStats['critico'];

        // Alertas de medicamentos críticos
        $medicamentosCriticos = Medicamento::getMedicamentosCriticos();

        $alertasMedicamentosCriticoStock = array_map(function ($m) {
            return [
                'title' => $m['nome'],
                'content' => 'Quantidade crítica: ' . $m['quantidade'],
            ];
        }, $medicamentosCriticos);

        // Estatísticas de Categorias, Raças e Espécies
        $totalCategorias = Categoria::getTotalCount();
        $totalRacas = RacaSearch::getTotalCount();
        $totalEspecies = EspecieSearch::getTotalCount();

        // Estatísticas de Marcações
        $totalmarcacoesHoje = MarcacaoSearch::getTotalHojeCount();
        $totalmarcacoesPendentes = MarcacaoSearch::getPendenteCount();

        // Listas de Marcações
        $ultimasMarcacoes = MarcacaoSearch::getUltimasMarcacoes();
        $marcacoesPendentes = MarcacaoSearch::getMarcacoesPendentesList();
        $marcacoesHoje = MarcacaoSearch::getMarcacoesHojeList();
        $totalmarcacoes = MarcacaoSearch::getTotalCount();

        // Estatísticas Financeiras do Mês
        $faturasDoMes = FaturaSearch::getFaturasDoMesCount();
        $receitaMensal = FaturaSearch::getReceitaMensal();

        // Dados para o gráfico de faturamento dos últimos 12 meses
        $dadosGrafico = FaturaSearch::getDadosFaturamentoAnual();
        $dadosFaturamento = $dadosGrafico['data'];
        $labelsMeses = $dadosGrafico['labels'];

        return $this->render('index', [
            'usertype' => $userType,
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
            'totalmarcacoesHoje' => $totalmarcacoesHoje,
            'totalmarcacoesPendentes' => $totalmarcacoesPendentes,
            'ultimasMarcacoes' => $ultimasMarcacoes,
            'marcacoesPendentes' => $marcacoesPendentes,
            'marcacoesHoje' => $marcacoesHoje,
            'faturasDoMes' => $faturasDoMes,
            'receitaMensal' => $receitaMensal,
            'totalmarcacoes' => $totalmarcacoes,
            'dadosFaturamento' => $dadosFaturamento,
            'labelsMeses' => $labelsMeses,
        ]);
    }
    private static function getusertype($userId) {
        if (!$userId) {
            return 0;
        }
        $roles = Yii::$app->authManager->getRolesByUser($userId);
        if (isset($roles['admin'])) {
            return 1;
        }
        if (isset($roles['veterinario'])) {
            return 2;
        }
        if (isset($roles['rececionista'])) {
            return 3;
        }
        return 0;
    }

    public static function export() {
        return self::getusertype(Yii::$app->user->id);
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

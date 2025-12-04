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
        $userType = $this->getusertype($userId);

        // Estatísticas de Clientes e Animais
        $totalClientes = Userprofile::find()->where(['eliminado' => 0])->count();
        $totalAnimais = Animal::find()->where(['eliminado' => 0])->count();

        // Estatísticas de Medicamentos
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

        // Alertas de medicamentos críticos
        $medicamentosCriticos = Medicamento::find()
            ->select(['nome', 'quantidade'])
            ->where(['<', 'quantidade', 5])
            ->andWhere(['eliminado' => 0])
            ->orderBy(['quantidade' => SORT_ASC])
            ->asArray()
            ->all();

        $alertasMedicamentosCriticoStock = array_map(function ($m) {
            return [
                'title' => $m['nome'],
                'content' => 'Quantidade crítica: ' . $m['quantidade'],
            ];
        }, $medicamentosCriticos);

        // Estatísticas de Categorias, Raças e Espécies
        $totalCategorias = Categoria::find()->where(['eliminado' => 0])->count();
        $totalRacas = Raca::find()->where(['eliminado' => 0])->count();
        $totalEspecies = Especie::find()->where(['eliminado' => 0])->count();

        // Estatísticas de Marcações
        $dataHoje = date('Y-m-d');
        $totalmarcacoesHoje = Marcacao::find()
            ->where(['DATE(data)' => $dataHoje])
            ->andWhere(['eliminado' => 0])
            ->count();
        $totalmarcacoesPendentes = Marcacao::find()
            ->where(['estado' => Marcacao::ESTADO_PENDENTE])
            ->andWhere(['eliminado' => 0])
            ->count();

        // Listas de Marcações
        $ultimasMarcacoes = Marcacao::find()
            ->where(['eliminado' => 0])
            ->orderBy(['data' => SORT_DESC])
            ->limit(5)
            ->all();
        $marcacoesPendentes = Marcacao::find()
            ->where(['estado' => Marcacao::ESTADO_PENDENTE, 'eliminado' => 0])
            ->asArray()
            ->all();
        $marcacoesHoje = Marcacao::find()
            ->where(['DATE(data)' => $dataHoje, 'eliminado' => 0])
            ->asArray()
            ->all();

        $totalmarcacoes = Marcacao::find()->where(['eliminado' => 0])->count();

        // Estatísticas Financeiras do Mês
        $inicioMes = strtotime(date('Y-m-01 00:00:00'));
        $fimMes = strtotime(date('Y-m-t 23:59:59'));

        $faturasDoMes = Fatura::find()
            ->where(['between', 'created_at', $inicioMes, $fimMes])
            ->andWhere(['eliminado' => 0])
            ->count();
        $receitaMensal = Fatura::find()
            ->where(['between', 'created_at', $inicioMes, $fimMes])
            ->andWhere(['eliminado' => 0])
            ->sum('total') ?? 0;

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

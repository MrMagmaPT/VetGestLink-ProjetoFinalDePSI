<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    //nome da aplicação
    'name' => 'VetGestLink',
    'bootstrap' => ['log'],
    'aliases' => [
        '@uploads' => '@app/../uploads',
    ],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        //assetManager com configuração para AdminLTE3
        'assetManager' => [
            'bundles' => [
                'hail812\adminlte3\bundles\AdminLteAsset',
                'hail812\adminlte3\bundles\PluginAsset',
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            //jsonParser
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['site/login'], // Adicione esta linha
        ],
        'session' => [
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //É aqui que se define as regras das urls
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // ==================== API VETGESTLINK ====================
                // Controllers: AnimalController, AuthController, EspecieController,
                //              FaturaController, HealthController, ImageController,
                //              MarcacaoController, MoradaController, NotaController,
                //              UserprofileController

                // ========== AUTENTICAÇÃO (Públicas - AuthController) ==========
                'POST api/auth/login' => 'api/auth/login',
                'POST api/auth/logout' => 'api/auth/logout',
                'POST api/auth/forgot-password' => 'api/auth/forgot-password',
                'GET api/auth/validate-token' => 'api/auth/validate-token',

                // ========== ANIMAIS (Protegidas - AnimalController) ==========
                'GET api/animal' => 'api/animal/index',
                'GET api/animal/<id:\d+>' => 'api/animal/view',
                'GET api/animal/<id:\d+>/notas' => 'api/animal/notas',

                // ========== ESPÉCIES E RAÇAS (Protegidas - EspecieController) ==========
                'GET api/especie' => 'api/especie/index',
                'GET api/especie/<id:\d+>/racas' => 'api/especie/racas',

                // ========== MARCAÇÕES (Protegidas - MarcacaoController) ==========
                'GET api/marcacao' => 'api/marcacao/index',
                'GET api/marcacao/<id:\d+>' => 'api/marcacao/view',

                // ========== FATURAS (Protegidas - FaturaController) ==========
                'GET api/fatura' => 'api/fatura/index',
                'GET api/fatura/<id:\d+>' => 'api/fatura/view',
                'POST api/fatura/<id:\d+>/pagamento' => 'api/fatura/pagamento',
                'GET api/fatura/resumo' => 'api/fatura/resumo',
                'GET api/fatura/metodos-pagamento' => 'api/fatura/metodos-pagamento',

                // ========== MORADAS (Protegidas - MoradaController) ==========
                'GET api/morada' => 'api/morada/index',
                'GET api/morada/<id:\d+>' => 'api/morada/view',
                'GET api/morada/principal' => 'api/morada/principal',
                'POST api/morada' => 'api/morada/create',
                'PUT api/morada/<id:\d+>' => 'api/morada/update',
                'DELETE api/morada/<id:\d+>' => 'api/morada/delete',

                // ========== NOTAS (Protegidas - NotaController) ==========
                'GET api/nota' => 'api/nota/index',
                'GET api/nota/<id:\d+>' => 'api/nota/view',
                'POST api/nota' => 'api/nota/create',
                'PUT api/nota/<id:\d+>' => 'api/nota/update',
                'DELETE api/nota/<id:\d+>' => 'api/nota/delete',

                // ========== PERFIL DO USUÁRIO (Protegidas - UserprofileController) ==========
                'GET api/userprofile' => 'api/userprofile/index',
                'GET api/userprofile/<id:\d+>' => 'api/userprofile/view',
                'PUT api/userprofile' => 'api/userprofile/update',
                'GET api/userprofile/estatisticas' => 'api/userprofile/estatisticas',

                // ========== HEALTH CHECK (Pública - HealthController) ==========
                'GET api/health' => 'api/health/index',

                // ========== IMAGENS (Públicas - ImageController) ==========
                'GET api/image/animal/<id:\d+>' => 'api/image/animal',
                'GET api/image/user/<id:\d+>' => 'api/image/user',
                'GET api/image/serve' => 'api/image/serve',
                'GET api/image/animals' => 'api/image/animals',
                'GET api/image/users' => 'api/image/users',
            ],
        ],
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => [
            'site/login',
            'site/error',
            // Excluir todas as rotas da API do filtro de acesso
            'api/*',
        ],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['backendAccess'], // Usuários autenticados com acesso ao backend
            ],
        ],
        'denyCallback' => function ($rule, $action) {
            if (Yii::$app->user->isGuest) {
                return Yii::$app->user->loginRequired();
            }
            throw new \yii\web\ForbiddenHttpException('Você não tem permissão para acessar esta página.');
        },
    ],
    'params' => $params,
];

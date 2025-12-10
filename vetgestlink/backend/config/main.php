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
        // apontar o alias @uploads para a pasta pública de uploads do frontend
        '@uploads' => '@frontend/web/uploads',
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
        // imageUploader is registered globally in common/config/main.php - no need to redefine here
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
                // ========== AUTENTICAÇÃO (Públicas - AuthController) ==========
                'POST api/auth/login' => 'api/auth/login',
                'POST api/auth/logout' => 'api/auth/logout',
                'GET api/auth/profile' => 'api/auth/profile',
                'POST api/auth/forgot' => 'api/auth/forgot',

                // ========== PERFIL (Protegidas - ProfileController) ==========
                'GET api/profile' => 'api/profile/index',
                'PUT api/profile/update' => 'api/profile/update',
                'PUT api/profile/password' => 'api/profile/password',

                // ========== ANIMAIS (Protegidas - AnimalController) ==========
                'GET api/animal/all' => 'api/animal/all',
                'GET api/animal/view/<id:\d+>' => 'api/animal/view',
                'GET api/animal/<id:\d+>/notes' => 'api/animal/notes',

                // ========== MARCAÇÕES (Protegidas - MarcacaoController) ==========
                'GET api/marcacao/all' => 'api/marcacao/all',
                'GET api/marcacao/view/<id:\d+>' => 'api/marcacao/view',

                // ========== FATURAS (Protegidas - FaturaController) ==========
                'GET api/fatura/all' => 'api/fatura/all',
                'GET api/fatura/view/<id:\d+>' => 'api/fatura/view',
                'GET api/fatura/paymentmethods' => 'api/fatura/paymentmethods',
                'PUT api/fatura/pay/<id:\d+>' => 'api/fatura/pay',

                // ========== NOTAS (Protegidas - NotaController) ==========
                'GET api/nota/all' => 'api/nota/all',
                'GET api/nota/view/<id:\d+>' => 'api/nota/view',
                'POST api/nota/create' => 'api/nota/create',
                'PUT api/nota/update/<id:\d+>' => 'api/nota/update',
                'DELETE api/nota/delete/<id:\d+>' => 'api/nota/delete',

                // ========== HEALTH CHECK (Pública - HealthController) ==========
                'GET api/health' => 'api/health/index',
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

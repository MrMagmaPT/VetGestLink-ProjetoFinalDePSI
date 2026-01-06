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
            //jsonParser() para requisições com Content-Type: application/json
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
                // ========== AUTH CONTROLLER (Público) ==========
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/auth',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST logout' => 'logout',
                        'POST forgot' => 'forgot',
                    ],
                ],
                // ========== PROFILE CONTROLLER (Protegido) ==========
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/profile',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'PUT update' => 'update',
                        'PUT password' => 'password',
                    ],
                ],
                // ========== ANIMAL CONTROLLER (Protegido) ==========
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/animal',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET all' => 'all',
                        'GET view/{id}' => 'view',
                        'GET {id}/notas' => 'notas',
                        'GET count' => 'count',
                        'GET nomes' => 'nomes',
                        'GET microchip/{microchip}' => 'pormicrochip',
                        'GET especie/{especie_id}' => 'porespecie',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{microchip}' => '<microchip:\\d+>',
                        '{especie_id}' => '<especie_id:\\d+>',
                    ],
                ],
                // ========== MARCACAO CONTROLLER (Protegido) ==========
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/marcacao',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET all' => 'all',
                        'GET view/{id}' => 'view',
                        'GET count' => 'count',
                        'GET estado/{estado}' => 'porestado',
                        'GET data/{dia}/{mes}/{ano}' => 'pordata',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{estado}' => '<estado:\\w+>',
                        '{dia}' => '<dia:\\d{1,2}>',
                        '{mes}' => '<mes:\\d{1,2}>',
                        '{ano}' => '<ano:\\d{4}>',
                    ],
                ],
                // ========== FATURA CONTROLLER (Protegido) ==========
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/fatura',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET all' => 'all',
                        'GET view/{id}' => 'view',
                        'GET paymentmethods' => 'paymentmethods',
                        'PUT pay/{id}' => 'pay',
                        'GET count' => 'count',
                        'GET total' => 'total',
                        'GET ano/{ano}' => 'porano',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{ano}' => '<ano:\\d{4}>',
                    ],
                ],
                // ========== NOTA CONTROLLER (Protegido - CRUD Completo) ==========
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/nota',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET all' => 'all',
                        'GET view/{id}' => 'view',
                        'POST create' => 'create',
                        'PUT update/{id}' => 'update',
                        'DELETE delete/{id}' => 'delete',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                // ========== LEMBRETE CONTROLLER (Protegido - CRUD Completo) ==========
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/lembrete',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET all' => 'all',
                        'GET view/{id}' => 'view',
                        'POST create' => 'create',
                        'PUT update/{id}' => 'update',
                        'DELETE delete/{id}' => 'delete',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
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

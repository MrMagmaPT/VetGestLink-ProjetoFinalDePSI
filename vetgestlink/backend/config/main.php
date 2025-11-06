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
                // API de Imagens
                'api/image/animal/<id:\d+>' => 'api/image/animal',
                'api/image/user/<id:\d+>' => 'api/image/user',
                'api/image/serve' => 'api/image/serve',
                'api/image/animals' => 'api/image/animals',
                'api/image/users' => 'api/image/users',

                // Outras APIs
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/user'],
                    'extraPatterns' => [
                        'POST login' => 'login',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/login', 'site/error'],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['backendAccess'], // Usuários autenticados

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

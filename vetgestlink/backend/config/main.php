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

                // AUTENTICAÇÃO (Públicas)
                'POST auth/login' => 'api/auth/login',
                'POST auth/logout' => 'api/auth/logout',
                'POST auth/forgot-password' => 'api/auth/forgot-password',
                'GET auth/validate-token' => 'api/auth/validate-token',

                // ANIMAIS (Protegidas)
                'GET client/animal' => 'api/client/animal',
                'GET client/animal/<id:\d+>' => 'api/client/animal',

                // MARCAÇÕES (Protegidas)
                'GET client/marcacao' => 'api/client/marcacao',
                'GET client/marcacao/<id:\d+>' => 'api/client/marcacao',

                // FATURAS (Protegidas)
                'GET client/fatura' => 'api/client/fatura',
                'GET client/fatura/<id:\d+>' => 'api/client/fatura',
                'POST client/fatura/<id:\d+>/pagamento' => 'api/client/pagamento',
                'GET client/fatura/resumo' => 'api/client/resumo',

                // MÉTODOS DE PAGAMENTO (Protegidas)
                'GET client/metodos-pagamento' => 'api/client/metodos-pagamento',

                // PERFIL (Protegidas)
                'GET client/perfil' => 'api/client/perfil',
                'PUT client/perfil' => 'api/client/update-perfil',
                'PUT client/morada' => 'api/client/update-morada',

                // NOTAS (Protegidas)
                'GET client/animal/<animal_id:\d+>/notas' => 'api/client/notas',
                'POST client/animal/<animal_id:\d+>/notas' => 'api/client/create-nota',
                'PUT client/notas/<id:\d+>' => 'api/client/update-nota',
                'DELETE client/notas/<id:\d+>' => 'api/client/delete-nota',

                // HEALTH CHECK (Pública)
                'GET health' => 'api/health/index',

                // ==================== API DE IMAGENS ====================

                'api/image/animal/<id:\d+>' => 'api/image/animal',
                'api/image/user/<id:\d+>' => 'api/image/user',
                'api/image/serve' => 'api/image/serve',
                'api/image/animals' => 'api/image/animals',
                'api/image/users' => 'api/image/users',
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

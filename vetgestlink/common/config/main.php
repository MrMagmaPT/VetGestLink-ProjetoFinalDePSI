<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',

        // Caminho físico absoluto para a pasta de uploads do backend
        '@uploads' => dirname(dirname(__DIR__)) . '/backend/web/uploads',
        // URL pública que aponta para a pasta acima (ajuste se necessário)
        // Ajustado para o caminho público real no ambiente local. Se usa VirtualHost, pode deixar como '/uploads'.
        '@uploadsUrl' => '/2_ano_1_semestre/Projeto/vetgestlink/backend/web/uploads',

    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        // Componente para gerir uploads de imagem (usa aliases configurados acima)
        'imageUploader' => [
            'class' => 'common\\components\\ImageUploader',
            'uploadPath' => '@uploads',     // alias para pasta física
            'baseUrl' => '@uploadsUrl',     // alias ou string com a URL pública
            'subdir' => 'users',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'smtp.gmail.com',
                'username' => 'vetgestlink@gmail.com',
                'password' => 'qaahplceeftgmsdu',
                'port' => 587,
                'encryption' => 'tls',
            ],
        ],
    ],
];

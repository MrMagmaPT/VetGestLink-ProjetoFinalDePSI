<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        // Pasta física para uploads dentro de `frontend/web/uploads`
        // Agora os ficheiros ficam acessíveis diretamente pela webroot do frontend
        '@uploads' => dirname(dirname(__DIR__)) . '/frontend/web/uploads',
        // URL pública que aponta para a pasta acima. Ajuste conforme o seu setup.
        '@uploadsUrl' => '/uploads',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\\rbac\\DbManager',
        ],
        // Componente para gerir uploads de imagem (usa aliases configurados acima)
        'imageUploader' => [
            'class' => 'common\\components\\ImageUploader',
            'uploadPath' => '@uploads',     // alias para pasta física (common/uploads)
            'baseUrl' => '@uploadsUrl',     // alias/URL pública para aceder aos ficheiros
            'subdir' => 'users',
            'defaultImage' => 'default.jpg', // ficheiro dentro do subdir usado quando não existir imagem
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

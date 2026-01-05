<?php
return [
    'id' => 'app-frontend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
        'session' => [
            'class' => 'frontend\tests\unit\_support\DummySession',
        ],
        'mailer' => [
            'messageClass' => \yii\symfonymailer\Message::class
        ]
    ],
];

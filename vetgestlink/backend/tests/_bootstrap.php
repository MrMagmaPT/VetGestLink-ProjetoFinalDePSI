<?php

// Iniciar output buffering para evitar problemas com sessões
ob_start();

// Desabilitar avisos de deprecação para os testes
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
//000000000000000000000000000000000000000000000000000000000000000000000000000000

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__.'/../../');

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';
require_once YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require_once YII_APP_BASE_PATH . '/common/config/bootstrap.php';
require_once __DIR__ . '/../config/bootstrap.php';

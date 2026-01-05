<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/backend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/common/config/test.php',
    require __DIR__ . '/common/config/test-local.php'
);
$application = new yii\web\Application($config);

// Buscar usuário admin
$user = \common\models\User::findByUsername('admin');
if ($user) {
    echo "User 'admin' found with ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Status: {$user->status}\n";
    echo "Password hash: {$user->password_hash}\n\n";
    
    // Testar senha
    if ($user->validatePassword('password_0')) {
        echo "✓ Password 'password_0' is VALID\n";
    } else {
        echo "✗ Password 'password_0' is INVALID\n";
    }
} else {
    echo "User 'admin' not found!\n";
}

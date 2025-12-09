<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\helpers\Html;

$faviconUrl = Yii::getAlias('@web') . '/favicon.ico';
$customCss = Yii::getAlias('@web') . '/static/css/blank.css';

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= $faviconUrl ?>">
    <!-- Custom CSS for blank layout -->
    <link rel="stylesheet" href="<?= $customCss ?>">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<main role="main">
    <div class="container">
        <?= $content ?>
    </div>
</main>
<?php $this->endBody() ?>
<!-- Pra carregar a opção de fechar o botão -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $this->endPage();

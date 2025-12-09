<?php
/** @var \yii\web\View $this */
/** @var string $content */

use frontend\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use frontend\widgets\ThemeAssets;
use frontend\widgets\ThemeScripts;
use frontend\widgets\Navbar;
use frontend\widgets\Footer;
use frontend\widgets\ScrollToTop;
//Registar assets comuns(fav)
use common\assets\CommonAsset;

AppAsset::register($this);
CommonAsset::register($this);
?>

<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>" class="no-js">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!--Configurar favicon-->
    <?php
    // Usar favicon publicado pelo asset bundle
    $commonAsset = common\assets\CommonAsset::register($this);
    $faviconUrl = $commonAsset->baseUrl . '/favicon.ico';
    ?>
    <link rel="shortcut icon" type="image/x-icon" href="<?= $faviconUrl ?>">
    
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <!-- Template CSS -->
    <?php ThemeAssets::widget(); ?>

    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?= Navbar::widget() ?>

<!-- Main content -->
<main role="main" class="flex-shrink-0 mt-5 pt-5">
    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show mt-2" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>

    <?= $content ?>
</main>

<?= Footer::widget() ?>

<?php ThemeScripts::widget(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
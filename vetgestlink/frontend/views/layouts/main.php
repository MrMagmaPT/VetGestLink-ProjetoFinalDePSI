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
use frontend\widgets\Preloader;
use frontend\widgets\ScrollToTop;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>" class="no-js">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>

    <?php
    $faviconUrl = Yii::getAlias('@web') . '/static/img/favicon.ico';
    ?>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= $faviconUrl ?>">

    <!-- Template CSS -->
    <?php ThemeAssets::widget(); ?>

    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?= Preloader::widget() ?>

<?= Navbar::widget() ?>

<!-- Main content -->
<main role="main" class="flex-shrink-0 mt-5 pt-5">
    <?= $content ?>
</main>

<?= Footer::widget() ?>

<?= ScrollToTop::widget() ?>

<?php ThemeScripts::widget(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
<?php
/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

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

    <!-- Favicon and Manifest -->
    <link rel="manifest" href="<?= Yii::getAlias('@web') ?>/site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="<?= Yii::getAlias('@web') ?>/assets/img/favicon.ico">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/slicknav.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/flaticon.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/animate.min.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/themify-icons.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/slick.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/nice-select.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/assets/css/style.css">

    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Preloader -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="<?= Yii::getAlias('@web') ?>/assets/img/logo/logo.png" alt="">
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header>
    <div class="header-area header-transparent">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-md-1">
                        <div class="logo">
                            <a href="<?= Yii::$app->homeUrl ?>">
                                <img src="<?= Yii::getAlias('@web') ?>/assets/img/logo/logo.png" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <div class="menu-main d-flex align-items-center justify-content-end">
                            <!-- Main menu -->
                            <div class="main-menu f-right d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>">Home</a></li>
                                        <li><a href="<?= Yii::$app->urlManager->createUrl(['site/about']) ?>">About</a></li>
                                        <li><a href="<?= Yii::$app->urlManager->createUrl(['site/contact']) ?>">Contact</a></li>

                                        <?php if (Yii::$app->user->isGuest): ?>
                                            <li><a href="<?= Yii::$app->urlManager->createUrl(['site/signup']) ?>">Signup</a></li>
                                            <li><a href="<?= Yii::$app->urlManager->createUrl(['site/login']) ?>">Login</a></li>
                                        <?php else: ?>
                                            <li><a href="#"><?= Html::encode(Yii::$app->user->identity->username) ?></a>
                                                <ul class="submenu">
                                                    <li>
                                                        <?= Html::beginForm(['/site/logout'], 'post')
                                                        . Html::submitButton('Logout', ['class' => 'btn btn-link logout text-dark'])
                                                        . Html::endForm() ?>
                                                    </li>
                                                </ul>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                <a href="#" class="header-btn">01654.066.456</a>
                            </div>
                        </div>
                    </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main content -->
<main role="main" class="flex-shrink-0 mt-5 pt-5">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],//bredcrubs para sair?
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<!-- Footer -->
<footer>
    <!-- Footer Start-->
    <div class="footer-area footer-padding">
        <div class="container">
            <div class="row d-flex justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <div class="single-footer-caption mb-50">
                        <div class="single-footer-caption mb-30">
                            <!-- logo -->
                            <div class="footer-logo mb-25">
                                <a href="index.html"><img src="assets/img/logo/logo2_footer.png" alt=""></a>
                            </div>
                            <div class="footer-tittle">
                                <div class="footer-pera">
                                    <p>Lorem ipsum dolor sit amet, adipiscing elit, sed do eiusmod tempor elit. </p>
                                </div>
                            </div>
                            <!-- social -->
                            <div class="footer-social">
                                <a href="https://www.facebook.com/sai4ull"><i class="fab fa-facebook-square"></i></a>
                                <a href="#"><i class="fab fa-twitter-square"></i></a>
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-pinterest-square"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Company</h4>
                            <ul>
                                <li><a href="index.html">Home</a></li>
                                <li><a href="about.html">About Us</a></li>
                                <li><a href="single-blog.html">Services</a></li>
                                <li><a href="#">Cases</a></li>
                                <li><a href="contact.html">  Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-7">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Services</h4>
                            <ul>
                                <li><a href="#">Commercial Cleaning</a></li>
                                <li><a href="#">Office Cleaning</a></li>
                                <li><a href="#">Building Cleaning</a></li>
                                <li><a href="#">Floor Cleaning</a></li>
                                <li><a href="#">Apartment Cleaning</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Get in Touch</h4>
                            <ul>
                                <li><a href="#">152-515-6565</a></li>
                                <li><a href="#"> Demomail@gmail.com</a></li>
                                <li><a href="#">New Orleans, USA</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>
<!-- Scroll Up -->
<div id="back-top" >
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

<!-- JS here -->

<script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
<!-- Jquery, Popper, Bootstrap -->
<script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="./assets/js/popper.min.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
<!-- Jquery Mobile Menu -->
<script src="./assets/js/jquery.slicknav.min.js"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="./assets/js/owl.carousel.min.js"></script>
<script src="./assets/js/slick.min.js"></script>
<!-- One Page, Animated-HeadLin -->
<script src="./assets/js/wow.min.js"></script>
<script src="./assets/js/animated.headline.js"></script>
<script src="./assets/js/jquery.magnific-popup.js"></script>

<!-- Nice-select, sticky -->
<script src="./assets/js/jquery.nice-select.min.js"></script>
<script src="./assets/js/jquery.sticky.js"></script>

<!-- contact js -->
<script src="./assets/js/contact.js"></script>
<script src="./assets/js/jquery.form.js"></script>
<script src="./assets/js/jquery.validate.min.js"></script>
<script src="./assets/js/mail-script.js"></script>
<script src="./assets/js/jquery.ajaxchimp.min.js"></script>

<!-- Jquery Plugins, main Jquery -->
<script src="./assets/js/plugins.js"></script>
<script src="./assets/js/main.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
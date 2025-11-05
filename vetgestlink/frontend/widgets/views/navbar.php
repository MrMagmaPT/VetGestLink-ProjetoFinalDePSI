<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<header>
    <div class="header-area header-transparent">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-md-1">
                        <div class="logo">
                            <a href="<?= Url::home() ?>">
                                <img src="<?= Yii::getAlias('@web') . $logoPath ?>" alt="VetGestLink">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <div class="menu-main d-flex align-items-center justify-content-end">
                            <!-- Menu principal -->
                            <div class="main-menu f-right d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <?php foreach ($menuItems as $item): ?>
                                            <li>
                                                <?= Html::a($item['label'], $item['url']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                <?php if (Yii::$app->user->isGuest): ?>
                                    <?= Html::a('Login/Signup', ['site/login'], ['class' => 'btn']) ?>
                                <?php else: ?>
                                    <?= Html::a('Logout', ['site/logout'], [
                                            'class' => 'btn',
                                            'data-method' => 'post'
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Menu Mobile -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

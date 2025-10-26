<?php
use yii\helpers\Html;
use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte3\assets\PluginAsset;

AdminLteAsset::register($this);
PluginAsset::register($this)->add(['fontawesome']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <?= Html::a('Home', ['/site/index'], ['class' => 'nav-link']) ?>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('<i class="fas fa-sign-in-alt"></i> Login', ['/site/login'], ['class' => 'nav-link']) ?>
                <?php else: ?>
                    <?= Html::a('<i class="fas fa-sign-out-alt"></i> Sair', ['/site/logout'], [
                            'data-method' => 'post',
                            'class' => 'nav-link'
                    ]) ?>
                <?php endif; ?>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= Yii::$app->homeUrl ?>" class="brand-link">
            <i class="fas fa-heartbeat brand-image" style="opacity: .8; margin-left: 10px; font-size: 2em;"></i>
            <span class="brand-text font-weight-light">VetGestLink</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle" style="color: #c2c7d0; font-size: 2.1em;"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= Yii::$app->user->isGuest ? 'Convidado' : Yii::$app->user->identity->username ?></a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <?= Html::a('<i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>', ['/site/index'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('<i class="nav-icon fas fa-users"></i><p>Clientes</p>', ['/clientes/index'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('<i class="nav-icon fas fa-paw"></i><p>Animais</p>', ['/animais/index'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('<i class="nav-icon fas fa-stethoscope"></i><p>Consultas</p>', ['/consultas/index'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('<i class="nav-icon fas fa-calendar-alt"></i><p>Agenda</p>', ['/agenda/index'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Gestão
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <?= Html::a('<i class="far fa-circle nav-icon"></i><p>Utilizadores</p>', ['/utilizadores/index'], ['class' => 'nav-link']) ?>
                            </li>
                            <li class="nav-item">
                                <?= Html::a('<i class="far fa-circle nav-icon"></i><p>Configurações</p>', ['/configuracoes/index'], ['class' => 'nav-link']) ?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>VetGestLink &copy; <?= date('Y') ?></strong> - Todos os direitos reservados.
        <div class="float-right d-none d-sm-inline-block">
            <b>Versão</b> 1.0.0
        </div>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

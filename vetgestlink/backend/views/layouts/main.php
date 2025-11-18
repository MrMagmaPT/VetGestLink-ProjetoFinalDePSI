<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <link rel="stylesheet" href="../../../web/static/css/layout.css">

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
                <a href="<?= Url::home() ?>" class="nav-link">Home</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <form action="<?= \yii\helpers\Url::to(['/site/logout']) ?>" method="post">
                    <?= \yii\helpers\Html::submitButton('<i class="fas fa-sign-out-alt"></i> Sair', [
                        'class' => 'nav-link',
                        'style' => 'background:none;border:none;padding:0;'
                    ]) ?>
                    <?= \yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= Url::home() ?>" class="brand-link">
            <i class="fas fa-heartbeat" style="color: #007bff; font-size: 1.8rem;"></i>
            <span class="brand-text">VetGestLink</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- User Panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle" style="font-size: 2.5rem; color: #6c757d;"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        <?= Yii::$app->user->isGuest ? 'Convidado' : Yii::$app->user->identity->username ?>
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <?= Html::a(
                            '<i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>',
                            ['/site/index'],
                            ['class' => 'nav-link ' . (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index' ? 'active' : '')]
                        ) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a(
                            '<i class="nav-icon fas fa-users"></i><p>UserProfiles</p>',
                            ['/userprofile/index'],
                            ['class' => 'nav-link ' . (Yii::$app->controller->id === 'userprofile' ? 'active' : '')]
                        ) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a(
                            '<i class="nav-icon fas fa-paw"></i><p>Animal</p>',
                            ['/animal/index'],
                            ['class' => 'nav-link ' . (Yii::$app->controller->id === 'animal' ? 'active' : '')]
                        ) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a(
                            '<i class="nav-icon fas fa-calendar-check"></i><p>Marcações</p>',
                            ['/marcacao/index'],
                            ['class' => 'nav-link ' . (Yii::$app->controller->id === 'marcacao' ? 'active' : '')]
                        ) ?>
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
                                <?= Html::a(
                                    '<i class="far fa-circle nav-icon"></i><p>Medicamentos</p>',
                                    ['/medicamento/index'],
                                    ['class' => 'nav-link ' . (Yii::$app->controller->id === 'medicamento' ? 'active' : '')]
                                ) ?>
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
        <strong>VetGestLink &copy; 2025</strong> - Todos os direitos reservados.
        <div class="float-right d-none d-sm-inline-block">
            <b>Versão</b> 1.0.0
        </div>
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

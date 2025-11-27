<?php
use yii\helpers\Html;
use yii\helpers\Url;

use backend\widgets\MenuItem;
use backend\widgets\MenuGroup;
use backend\controllers\SiteController;
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>
        
    <?= Html::encode($this->title) ?>

    </title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <!-- Favicon -->
    <?php
    $faviconUrl = Yii::getAlias('@web') . '/favicon.ico';
    ?>
    <link rel="shortcut icon" type="image/x-icon" href="<?= $faviconUrl ?>">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../../web/static/css/layout.css">

    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>
<?php
    $usertype = SiteController::export();
?>
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
    <!--
    <div class="info">
        <form action="<?= \yii\helpers\Url::to(['/site/logout']) ?>" method="post">
            <?= \yii\helpers\Html::submitButton('<i class="fas fa-sign-out-alt"></i> Sair', [
            'class' => 'nav-link',
            'style' => 'background:none;border:none;padding:0;'
            ]) ?>
            <?= \yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
        </form>
    </div>
    -->
    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= Url::home() ?>" class="brand-link">
            <i class="fas fa-heartbeat" style="color: #007bff; font-size: 1.8rem;"></i>
            <span class="brand-text">VetGestLink</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php if ($usertype == 1): ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-cubes-stacked"></i><p>Dashboard</p>',
                            'url' => ['/site/index'],
                            'active' => Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-circle-user"></i><p>Gestao de Utilizadores</p>',
                            'url' => ['/userprofile/index'],
                            'active' => Yii::$app->controller->id === 'userprofile' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-pills"></i><p>Medicamentos</p>',
                            'url' => ['/medicamento/index'],
                            'active' => Yii::$app->controller->id === 'medicamento' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-folder"></i><p>Categoria de Medicamentos</p>',
                            'url' => ['/categoria/index'],
                            'active' => Yii::$app->controller->id === 'categoria' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-credit-card"></i><p>Metodos de Pagamentos</p>',
                            'url' => ['/metodopagamento/index'],
                            'active' => Yii::$app->controller->id === 'metodopagamento' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                    <?php endif; ?>
                    <?php if ($usertype == 2): ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-cubes-stacked"></i><p>Dashboard</p>',
                            'url' => ['/site/index'],
                            'active' => Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-paw"></i><p>Animais</p>',
                            'url' => ['/animal/index'],
                            'active' => Yii::$app->controller->id === 'animal' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon far fa-file-lines"></i><p>Consulta</p>',
                            'url' => ['/marcacao/index'],
                            'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon far fa-calendar"></i><p>Agendamentos</p>',
                            'url' => ['/marcacao/index'],
                            'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-pills"></i><p>Medicamentos</p>',
                            'url' => ['/medicamento/index'],
                            'active' => Yii::$app->controller->id === 'medicamento' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuGroup::widget([
                            'label' => 'Raças & Espécies',
                            'icon' => 'nav-icon fas fa-layer-group',
                            'items' => [
                                [
                                    'label' => '<i class="fas nav-icon fa-paw"></i><p>Raças</p>',
                                    'url' => ['/raca/index'],
                                    'active' => Yii::$app->controller->id === 'raca',
                                    'encodeLabel' => false,
                                ],[
                                    'label' => '<i class="fas nav-icon fa-layer-group"></i><p>Espécies</p>',
                                    'url' => ['/especie/index'],
                                    'active' => Yii::$app->controller->id === 'especie',
                                    'encodeLabel' => false,
                                ],
                            ],
                        ]) ?>
                    <?php endif; ?>
                    <?php if ($usertype == 3): ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-cubes-stacked"></i><p>Dashboard</p>',
                            'url' => ['/site/index'],
                            'active' => Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-paw"></i><p>Animais</p>',
                            'url' => ['/animal/index'],
                            'active' => Yii::$app->controller->id === 'animal' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon far fa-file-lines"></i><p>Consulta</p>',
                            'url' => ['/marcacao/index'],
                            'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon far fa-calendar"></i><p>Agendamentos</p>',
                            'url' => ['/marcacao/index'],
                            'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuItem::widget([
                            'label' => '<i class="nav-icon fa fa-pills"></i><p>Medicamentos</p>',
                            'url' => ['/medicamento/index'],
                            'active' => Yii::$app->controller->id === 'medicamento' && Yii::$app->controller->action->id === 'index',
                            'encodeLabel' => false,
                        ]) ?>
                        <?= MenuGroup::widget([
                            'label' => 'Faturas',
                            'icon' => 'nav-icon fas fa-layer-group',
                            'items' => [
                                [
                                    'label' => '<i class="far nav-icon fa-clock"></i><p>Por Pagar</p>',
                                    'url' => ['/fatura/index'],
                                    'active' => Yii::$app->controller->id === 'fatura',
                                    'encodeLabel' => false,
                                ],[
                                    'label' => '<i class="fas nav-icon fa-circle-check"></i><p>Pagas</p>',
                                    'url' => ['/fatura/index'],
                                    'active' => Yii::$app->controller->id === 'fatura' && Yii::$app->controller->action->id === 'index',
                                    'encodeLabel' => false,
                                ],
                            ],
                        ]) ?>
                    <?php endif; ?>
                </ul>
            </nav>
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

<!-- Scripts: let Yii asset manager register jQuery and other assets -->
<!-- If you need CDN versions adjust asset bundles; avoid manual jQuery include here -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

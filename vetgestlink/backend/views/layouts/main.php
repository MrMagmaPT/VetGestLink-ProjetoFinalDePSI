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
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php if ($usertype == 1): ?> <!-- Admin -->
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-cubes-stacked',
                            'text' => 'Dashboard',
                            'url' => ['/site/index'],
                            'active' => Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-circle-user',
                            'text' => 'Gestao de Utilizadores',
                            'url' => ['/userprofile/index'],
                            'active' => Yii::$app->controller->id === 'userprofile' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-pills',
                            'text' => 'Medicamentos',
                            'url' => ['/medicamento/index'],
                            'active' => Yii::$app->controller->id === 'medicamento' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'far fa-folder',
                            'text' => 'Categoria de Medicamentos',
                            'url' => ['/categoria/index'],
                            'active' => Yii::$app->controller->id === 'categoria' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-credit-card',
                            'text' => 'Metodos de Pagamentos',
                            'url' => ['/metodopagamento/index'],
                            'active' => Yii::$app->controller->id === 'metodopagamento' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                    <?php endif; ?>
                    <?php if ($usertype == 2): ?> <!-- Veterinario -->
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-cubes-stacked',
                            'text' => 'Dashboard',
                            'url' => ['/site/index'],
                            'active' => Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-paw',
                            'text' => 'Animais',
                            'url' => ['/animal/index'],
                            'active' => Yii::$app->controller->id === 'animal' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'far fa-file-lines',
                            'text' => 'Consulta',
                            'url' => ['/marcacao/index'],
                            'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'far fa-calendar',
                            'text' => 'Agendamentos',
                            'url' => ['/marcacao/index'],
                            'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-pills',
                            'text' => 'Medicamentos',
                            'url' => ['/medicamento/index'],
                            'active' => Yii::$app->controller->id === 'medicamento' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuGroup::widget([
                            'text' => 'Raças & Espécies',
                            'icon' => 'nav-icon fas fa-layer-group',
                            'subs' => [
                                [
                                    'type' =>  '2',
                                    'icon' => 'fas fa-paw',
                                    'text' => 'Raças',
                                    'url' => ['/raca/index'],
                                    'active' => Yii::$app->controller->id === 'raca',
                                ],[
                                    'type' =>  '2',
                                    'icon' => 'fas fa-layer-group',
                                    'text' => 'Espécies',
                                    'url' => ['/especie/index'],
                                    'active' => Yii::$app->controller->id === 'especie',
                                ],
                            ],
                        ]) ?>
                    <?php endif; ?>
                    <?php if ($usertype == 3): ?> <!-- Rececionista -->
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-cubes-stacked',
                            'text' => 'Dashboard',
                            'url' => ['/site/index'],
                            'active' => Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-paw',
                            'text' => 'Animais',
                            'url' => ['/animal/index'],
                            'active' => Yii::$app->controller->id === 'animal' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuGroup::widget([
                            'icon' => 'far fa-file-lines',
                            'text' => 'Consultas',
                            'subs' => [
                                [
                                    'type' => '1',
                                    'icon' => 'fas fa-file-lines',
                                    'text' => 'Consultas Terminadas',
                                    'subs' => [
                                        [
                                            'type' => '2',
                                            'icon' => 'fas fa-file-lines',
                                            'text' => 'Com fatura',
                                            'url' => ['/marcacao/index'],
                                            'active' => Yii::$app->controller->id === 'marcacao',
                                        ],
                                        [
                                            'type' => '2',
                                            'icon' => 'fas fa-file-lines',
                                            'text' => 'Sem Fatura',
                                            'url' => ['/marcacao/index'],
                                            'active' => Yii::$app->controller->id === 'marcacao',
                                        ],
                                    ],
                                ],
                                [
                                    'type' => '2',
                                    'icon' => 'fas fa-file-lines',
                                    'text' => 'Consultas Canceladas',
                                    'url' => ['/marcacao/index'],
                                    'active' => Yii::$app->controller->id === 'marcacao',
                                ],
                            ],
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'far fa-calendar',
                            'text' => 'Agendamentos',
                            'url' => ['/marcacao/index'],
                            'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuItem::widget([
                            'icon' => 'fa fa-pills',
                            'text' => 'Medicamentos',
                            'url' => ['/medicamento/index'],
                            'active' => Yii::$app->controller->id === 'medicamento' && Yii::$app->controller->action->id === 'index',
                        ]) ?>
                        <?= MenuGroup::widget([
                            'text' => 'Faturas',
                            'icon' => 'nav-icon fas fa-layer-group',
                            'subs' => [
                                [
                                    'type' =>  '2',
                                    'icon' => 'fas fa-clock',
                                    'text' => 'Por Pagar',
                                    'url' => ['/fatura/index'],
                                    'active' => Yii::$app->controller->id === 'fatura',
                                ],[
                                    'type' =>  '2',
                                    'icon' => 'fas fa-circle-check',
                                    'text' => 'Pagas',
                                    'url' => ['/fatura/index'],
                                    'active' => Yii::$app->controller->id === 'fatura',
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

<!-- Scripts: let Yii asset manager register jQuery and other assets -->
<!-- If you need CDN versions adjust asset bundles; avoid manual jQuery include here -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

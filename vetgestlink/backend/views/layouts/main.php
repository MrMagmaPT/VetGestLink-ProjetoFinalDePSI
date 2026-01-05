<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\widgets\MenuItem;
use backend\widgets\MenuGroup;
use backend\controllers\SiteController;
use common\assets\CommonAsset;
use kartik\select2\Select2Asset;
use common\components\fullcalendar\FullcalendarWidget;

//Registar assets comuns()
CommonAsset::register($this);
Select2Asset::register($this);

//Pegar o favicon 
$faviconUrl = Yii::getAlias('@web') . '/favicon.ico';

$this->beginPage();
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Meta Tags -->
        <?= Html::csrfMetaTags() ?>
        <title>
            <?= Html::encode("Backend " . $this->title) ?>
        </title>
        <!-- AdminLTE CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="<?= $faviconUrl ?>">

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
                    <a href="<?= Url::home() ?>" class="nav-link">Dashboard</a>
                </li>
            </ul>
            <!-- Fullscreen-->
            <ul class="navbar-nav ml-auto">
                <!-- Logout -->
                <li class="nav-item">
                    <form action="<?=Url::to(['/site/logout']) ?>" method="post">
                        <?= Html::submitButton(
                            '<i class="fas fa-sign-out-alt"></i> Log Out',
                            [
                                'class' => 'nav-link',
                                'style' => 'background:none;border:none;padding:0;'
                        ]) ?>
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                    </form>
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
            <!-- VetGestLink Logo -->
            <a href="<?= Url::home() ?>" class="brand-link" style="text-decoration: none;">
                <img src="<?= $faviconUrl ?>" alt="Logo" class="brand-image">
                <span class="brand-text text-success"><b>VetGestLink</b></span>
                <!-- Botão de Logout compacto -->
                <form action="<?= Url::to(['/site/logout']) ?>" method="post" style="display:inline;">
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Sair" style="padding:2px 6px; font-size:0.9rem; margin-left:12px;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- User Panel -->
                <div class="user-panel d-flex" style="padding:4px 4px;">
                    <div class="image pl-0">
                        <?php
                        $userImage = null;
                        if (!Yii::$app->user->isGuest) {
                            $userprofile = isset($userprofile) ? $userprofile : \common\models\Userprofile::findOne(['user_id' => Yii::$app->user->id]);
                            if ($userprofile && !empty($userprofile->getImageUrl())) {
                                $userImage = $userprofile->getImageUrl();
                            }
                        }
                        ?>
                        <?php if ($userImage): ?>
                            <!-- Foi usado Style inline porque através do .css não estava a funcionar -->
                            <img src="<?= $userImage ?>" alt="User Image" class="img-circle" style="width: 2.5rem; height: 2.5rem; object-fit: cover; margin-left: 8px;">
                        <?php else: ?>
                            <i class="fas fa-user-circle" style="font-size: 2.5rem; color: #c2c7d0;"></i>
                        <?php endif; ?>
                    </div>
                    <div class="info">
                        <!-- Construir link para o perfil do utilizador -->
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?php 
                                $profileUrl = isset($userprofile) && $userprofile ? Url::to(['/userprofile/view', 'id' => $userprofile->id]) : '#';
                                $user = Yii::$app->user->identity;
                            ?>
                            <div class="d-flex align-items-center">
                                <a href="<?= $profileUrl ?>" class="d-block mr-2" style="text-decoration: none;">
                                    <?= isset($user->username) ? Html::encode($user->username) : '' ?>
                                </a>
                            </div>
                        <?php else: ?>
                            <a href="#" class="d-block">Convidado</a>
                        <?php endif; ?>
                    </div>
                </div>
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
                                    'icon' => 'fa fas fa-users',
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
                            <?= MenuItem::widget([
                                    'icon' => 'fa fa-concierge-bell',
                                    'text' => 'Serviços',
                                    'url' => ['/servico/index'],
                                    'active' => Yii::$app->controller->id === 'servico' && Yii::$app->controller->action->id === 'index',
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
                                    'text' => 'Marcações',
                                    'url' => ['/marcacao/index'],
                                    'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                            <?= MenuItem::widget([
                                    'icon' => 'fa fa-pills',
                                    'text' => 'Medicamentos',
                                    'url' => ['/medicamento/index'],
                                    'active' => Yii::$app->controller->id === 'medicamento' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                            <?= MenuItem::widget([
                                    'icon' => 'fa fa-concierge-bell',
                                    'text' => 'Serviços',
                                    'url' => ['/servico/index'],
                                    'active' => Yii::$app->controller->id === 'servico' && Yii::$app->controller->action->id === 'index',
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
                                    'icon' => 'fa fa-circle-user',
                                    'text' => 'Gestao de Clientes',
                                    'url' => ['/userprofile/index'],
                                    'active' => Yii::$app->controller->id === 'userprofile' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                            <?= MenuItem::widget([
                                    'icon' => 'fa fa-paw',
                                    'text' => 'Animais',
                                    'url' => ['/animal/index'],
                                    'active' => Yii::$app->controller->id === 'animal' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                            <?= MenuItem::widget([
                                    'icon' => 'fa fa-concierge-bell',
                                    'text' => 'Serviços',
                                    'url' => ['/servico/index'],
                                    'active' => Yii::$app->controller->id === 'servico' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                            <?= MenuItem::widget([
                                    'icon' => 'fa fa-credit-card',
                                    'text' => 'Metodos de Pagamentos',
                                    'url' => ['/metodopagamento/index'],
                                    'active' => Yii::$app->controller->id === 'metodopagamento' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                            <?= MenuItem::widget([
                                    'icon' => 'far fa-calendar',
                                    'text' => 'Marcacoes',
                                    'url' => ['/marcacao/index'],
                                    'active' => Yii::$app->controller->id === 'marcacao' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                            <?= MenuItem::widget([
                                    'icon' => 'nav-icon fas fa-layer-group',
                                    'text' => 'Faturas',
                                    'url' => ['/fatura/index'],
                                    'active' => Yii::$app->controller->id === 'fatura' && Yii::$app->controller->action->id === 'index',
                            ]) ?>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <?= $content ?>
        </div>
        <!-- Footer -->
        <footer class="main-footer text-center">
            <strong >VetGestLink &copy; 2025</strong> - Todos os direitos reservados.
            <div class="float-right d-none d-sm-inline-block">
                <b>Versão</b> 1.0.0
            </div>
        </footer>
        
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <!-- O conteúdo será gerado dinamicamente pelo control_sidebar.js -->
        </aside>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <?php
    // Registrar o control_sidebar.js
    $this->registerJsFile(
        '@web/../vendor/hail812/yii2-adminlte3/src/web/js/control_sidebar.js',
        ['depends' => [\yii\web\JqueryAsset::class]]
    );
    ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
<?php
use yii\helpers\Html;
use backend\widgets\CardsContainerWidget;
use backend\widgets\BigCardWidget;
use backend\widgets\QuickActionContainerWidget;
use backend\widgets\AlertContainerWidget;
use \backend\widgets\TableWidget;

$this->title = 'Dashboard';

// Defaults to avoid undefined variables
$totalClientes = $totalClientes ?? 0;
$totalAnimais = $totalAnimais ?? 0;
$marcacoesHoje = $marcacoesHoje ?? 0;
$marcacoesPendentes = $marcacoesPendentes ?? 0;
$totalMedicamentos = $totalMedicamentos ?? 0;
$totalCategorias = $totalCategorias ?? 0;
$totalRacas = $totalRacas ?? 0;
$totalEspecies = $totalEspecies ?? 0;
$faturasDoMes = $faturasDoMes ?? 0;
$receitaMensal = $receitaMensal ?? 0;
$ultimasMarcacoes = $ultimasMarcacoes ?? [];

$this->registerCssFile('@web/static/css/view.css');

?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Dashboard</h1></div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if ($usertype == 1): ?> <!-- Admin -->
            <div class="row">
                <?php
                    echo BigCardWidget::widget([
                        'icon' => 'fa-pills',
                        'iconColorClass' => 'bg-primary',
                        'text' => 'Medicamentos',
                        'value' => $totalMedicamentos,
                        'url' => '/medicamento/index',
                    ]);
                ?>
                <?php
                    echo BigCardWidget::widget([
                        'icon' => 'fa-users',
                        'iconColorClass' => 'icon-purple',
                        'text' => 'Clientes',
                        'value' => $totalClientes,
                        'url' => '/userprofile/index',
                    ]);
                ?>
            </div>
            <!-- Stock de Inventário -->
            <div class="row mt-4">
            <?php
                echo CardsContainerWidget::widget([
                    'text' => 'Stock de Inventário',
                    'url' => '/medicamento/index',
                    'buttontext' => 'Ver Tudo',
                    'buttonclass' => 'btn btn-sm btn-primary',
                    'cards' => [
                        [
                            'value' => 3,
                            'text' => 'Crítico',
                            'icon' => 'fa-skull-crossbones',
                            'iconColorClass' => 'icon-red',
                        ],
                        [
                            'value' => 5,
                            'text' => 'Baixo',
                            'icon' => 'fa-exclamation-triangle',
                            'iconColorClass' => 'icon-orange',
                        ],
                        [
                            'value' => $totalMedicamentos,
                            'text' => 'Em Estoque',
                            'icon' => 'fa-check-circle',
                            'iconColorClass' => 'icon-blue',
                        ],
                    ],
                ]);

            ?>
                <div class="col-lg-4 col-12">

                    <?php
                        echo QuickActionContainerWidget::widget([
                            'text' => 'Ações Rápidas',
                            'options' => [
                                ['text' => 'Adicionar Utilizador', 'icon' => 'fa-user-plus', 'url' => '/userprofile/create'],
                                ['text' => 'Adicionar Medicamento', 'icon' => 'fa-pills', 'url' => '/medicamento/create'],
                            ],
                        ]);

                        echo AlertContainerWidget::widget([
                            'text' => 'Alertas',
                            'options' => [
                                [
                                    'title' => 'Alerta de Stock Baixo',
                                    'message' => 'Vacina anti-rábica com stock reduzido',
                                    'icon' => 'fa-exclamation-circle',
                                    'class' => 'text-danger',
                                ],
                                [
                                    'title' => 'Alerta de Stock Baixo',
                                    'message' => 'Vacina anti-rábica com stock reduzido',
                                    'icon' => 'fa-exclamation-circle',
                                    'class' => 'text-danger',
                                ],
                            ],
                        ]);
                    ?>
                </div>
            </div>

            <!-- Últimas marcações table -->
            <div class=".col-md-12">
                <?php
                    echo TableWidget::widget([
                        'title' => 'Marcações',
                        'content' => [$marcacoesHoje],
                    ]);
                ?>
            </div>
        <?php endif; ?>

        <?php if ($usertype == 2): ?> <!-- Veterinarian -->
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon bg-green rounded"><i class="fas fa-paw"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Animais</span>
                            <span class="info-box-number"><?= $totalAnimais ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon bg-yellow rounded"><i class="fas fa-stethoscope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Consultas Hoje</span>
                            <span class="info-box-number"><?= $marcacoesHoje ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-8 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon icon-purple rounded"><i class="fas fa-pills"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Medicamentos</span>
                            <span class="info-box-number"><?= $totalMedicamentos ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon bg-navy rounded"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Raças</span>
                            <span class="info-box-number"><?= $totalRacas ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon bg-olive rounded"><i class="fas fa-paw"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Espécies</span>
                            <span class="info-box-number"><?= $totalEspecies ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas marcações table -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header"><h3 class="card-title">Últimas Marcações</h3></div>
                        <div class="card-body">
                            <?php if (!empty($ultimasMarcacoes)): ?>
                                <table class="table table-striped">
                                    <thead><tr>
                                        <th>Data</th><th>Animal</th><th>Cliente</th><th>Tipo</th><th>Estado</th>
                                    </tr></thead>
                                    <tbody>
                                    <?php foreach ($ultimasMarcacoes as $marcacao): ?>
                                        <tr>
                                            <td><?= Yii::$app->formatter->asDatetime($marcacao->data, 'dd/MM/yyyy HH:mm') ?></td>
                                            <td><?= Html::encode($marcacao->animais->nome ?? 'N/A') ?></td>
                                            <td><?= Html::encode($marcacao->userprofiles->nome ?? 'N/A') ?></td>
                                            <td><?= Html::encode($marcacao->tipo) ?></td>
                                            <td><span class="label label-<?= $marcacao->estado === 'Pendente' ? 'warning' : 'success' ?>"><?= Html::encode($marcacao->estado) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Nenhuma marcação registrada.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($usertype == 3): ?> <!-- Receptionist -->
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon icon-purple rounded"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Clientes</span>
                            <span class="info-box-number"><?= $totalClientes ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon bg-yellow rounded"><i class="fas fa-stethoscope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Marcações Hoje</span>
                            <span class="info-box-number"><?= $marcacoesHoje ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12">
                    <div class="info-box info-box-custom shadow-sm">
                        <span class="info-box-icon bg-red rounded"><i class="fas fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Marcações Pendentes</span>
                            <span class="info-box-number"><?= $marcacoesPendentes ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas marcações table -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header"><h3 class="card-title">Últimas Marcações</h3></div>
                        <div class="card-body">
                            <?php if (!empty($ultimasMarcacoes)): ?>
                                <table class="table table-striped">
                                    <thead><tr>
                                        <th>Data</th><th>Animal</th><th>Cliente</th><th>Tipo</th><th>Estado</th>
                                    </tr></thead>
                                    <tbody>
                                    <?php foreach ($ultimasMarcacoes as $marcacao): ?>
                                        <tr>
                                            <td><?= Yii::$app->formatter->asDatetime($marcacao->data, 'dd/MM/yyyy HH:mm') ?></td>
                                            <td><?= Html::encode($marcacao->animais->nome ?? 'N/A') ?></td>
                                            <td><?= Html::encode($marcacao->userprofiles->nome ?? 'N/A') ?></td>
                                            <td><?= Html::encode($marcacao->tipo) ?></td>
                                            <td><span class="label label-<?= $marcacao->estado === 'Pendente' ? 'warning' : 'success' ?>"><?= Html::encode($marcacao->estado) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Nenhuma marcação registrada.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>


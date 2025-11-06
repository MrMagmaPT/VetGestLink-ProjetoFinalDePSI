<?php
use yii\helpers\Html;

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
?>

<style>
    /* Paste your original first style block here */
    .info-box-custom {
        border-radius: 10px;
        padding: 20px;
    }
    .stock-card {
        text-align: center;
        padding: 30px;
        border-radius: 10px;
        background: #f8f9fa;
    }
    .stock-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 15px;
    }
    .icon-blue { background: #007bff; }
    .icon-orange { background: #ff9800; }
    .icon-red { background: #dc3545; }
    .icon-purple { background: #9333ea; }

    .alert-custom {
        background: #fee;
        border-left: 4px solid #dc3545;
        padding: 15px;
        border-radius: 5px;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .quick-action-btn:hover {
        background: #f8f9fa;
    }
</style>

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
                    echo \backend\widgets\MainCardIndexWidget::widget([
                        'icon' => 'fa-pills',
                        'iconColorClass' => 'bg-primary',
                        'text' => 'Medcicamentos',
                        'value' => $totalMedicamentos,
                        'url' => '/medicamento/index',
                    ]);
                ?>
                <?php
                    echo \backend\widgets\MainCardIndexWidget::widget([
                        'icon' => 'fa-users',
                        'iconColorClass' => 'icon-purple',
                        'text' => 'Clientes',
                        'value' => $totalClientes,
                        'url' => '/userprofile/index',
                    ]);
                ?>
            </div>

            <div class="row mt-4">
                <div class="col-lg-8 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Stock de Inventário</h3>
                            <div class="card-tools">
                                <?= Html::a('Ver Tudo', ['/medicamento/index'], ['class' => 'btn btn-sm btn-primary']) ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stock-card">
                                        <div class="stock-icon icon-blue"><i class="fas fa-box"></i></div>
                                        <h2 class="mb-0"><?= $totalMedicamentos ?></h2>
                                        <p class="text-muted">Total de Itens</p>
                                    </div>
                                </div>
                                <!-- Add placeholders or dynamic counts for low/critical stock as needed -->
                                <div class="col-md-4">
                                    <div class="stock-card">
                                        <div class="stock-icon icon-orange"><i class="fas fa-box-open"></i></div>
                                        <h2 class="mb-0">8</h2>
                                        <p class="text-muted">Stock Baixo</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stock-card">
                                        <div class="stock-icon icon-red"><i class="fas fa-exclamation-triangle"></i></div>
                                        <h2 class="mb-0">3</h2>
                                        <p class="text-muted">Crítico</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header"><h3 class="card-title">Ações Rápidas</h3></div>
                        <div class="card-body">
                            <?= Html::a('<i class="fas fa-user-plus"></i> Adicionar Utilizador', ['/userprofile/create'], ['class' => 'quick-action-btn']) ?>
                            <?= Html::a('<i class="fas fa-pills"></i> Adicionar Medicamento', ['/medicamento/create'], ['class' => 'quick-action-btn']) ?>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-3">
                        <div class="card-header"><h3 class="card-title">Alertas</h3></div>
                        <div class="card-body">
                            <div class="alert-custom">
                                <i class="fas fa-exclamation-circle text-danger"></i>
                                <strong>Alerta de Stock Baixo</strong><br>
                                <small>Vacina anti-rábica com stock reduzido</small>
                            </div>
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


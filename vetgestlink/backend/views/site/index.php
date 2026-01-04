<?php
use yii\helpers\Html;
use backend\widgets\CardsContainerWidget;
use backend\widgets\BigCardWidget;
use backend\widgets\QuickActionContainerWidget;
use backend\widgets\AlertContainerWidget;
use backend\widgets\TableWidget;
use backend\widgets\SmallCardWidget;

$this->title = 'Dashboard';

$totalClientes = $totalClientes ?? 0;
$totalAnimais = $totalAnimais ?? 0;
$totalMedicamentos = $totalMedicamentos ?? 0;
$totalMedicamentosEmStock = $totalMedicamentosEmStock ?? 0;
$totalMedicamentosBaixoStock = $totalMedicamentosBaixoStock ?? 0;
$totalMedicamentosCriticoStock = $totalMedicamentosCriticoStock ?? 0;
$alertasMedicamentosCriticoStock = $alertasMedicamentosCriticoStock ?? [];
$totalCategorias = $totalCategorias ?? 0;
$totalRacas = $totalRacas ?? 0;
$totalEspecies = $totalEspecies ?? 0;
$totalmarcacoes = $totalmarcacoes ?? 0;
$totalmarcacoesHoje = $totalmarcacoesHoje ?? 0;
$totalmarcacoesPendentes = $totalmarcacoesPendentes ?? 0;
$ultimasMarcacoes = $ultimasMarcacoes ?? [];
$marcacoesPendentes = $marcacoesPendentes ?? [];
$marcacoesHoje = $marcacoesHoje ?? [];
$faturasDoMes = $faturasDoMes ?? 0;
$receitaMensal = $receitaMensal ?? 0;

$this->registerCssFile('@web/static/css/view.css');

?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">
            <i class="fas fa-tachometer-alt text-primary"></i>    
            Dashboard</h1></div>
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
                                        'value' => $totalMedicamentosCriticoStock,
                                        'text' => 'Crítico',
                                        'icon' => 'fa-skull-crossbones',
                                        'iconColorClass' => 'icon-red',
                                ],
                                [
                                        'value' => $totalMedicamentosBaixoStock,
                                        'text' => 'Baixo',
                                        'icon' => 'fa-exclamation-triangle',
                                        'iconColorClass' => 'icon-orange',
                                ],
                                [
                                        'value' => $totalMedicamentosEmStock,
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
                                            'content' => $alertasMedicamentosCriticoStock,
                                            'icon' => 'fa-exclamation-circle',
                                            'class' => 'text-danger',
                                    ],
                            ],
                    ]);
                    ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($usertype == 2): ?> <!-- Veterinarian -->
            <div class="row">
                <?php
                echo SmallCardWidget::widget([
                        'icon' => 'fa-pills',
                        'iconColorClass' => 'icon-blue',
                        'text' => 'Medicamentos',
                        'value' => $totalMedicamentos,
                        'url' => '/medicamento/index',
                ]);
                echo SmallCardWidget::widget([
                        'icon' => 'fa-paw',
                        'iconColorClass' => 'icon-green',
                        'text' => 'Animais',
                        'value' => $totalAnimais,
                        'url' => '/animal/index',
                ]);
                echo SmallCardWidget::widget([
                        'icon' => 'fa-calendar-check',
                        'iconColorClass' => 'icon-orange',
                        'text' => 'Marcações',
                        'value' => $totalmarcacoesHoje,
                        'url' => '/marcacao/index',
                ]);
                echo SmallCardWidget::widget([
                        'icon' => 'fa-clock',
                        'iconColorClass' => 'icon-red',
                        'text' => 'Marcações Pendentes',
                        'value' => $totalmarcacoesPendentes,
                        'url' => '/marcacao/index',
                ]);
                ?>
            </div>
            <div class="row mt-4">
                <?php
                echo TableWidget::widget([
                        'title' => 'Marcações Pendentes',
                        'content' => $marcacoesPendentes,
                        'columns' => ['data','estado','horainicio', 'horafim', 'servicos_id', 'animais_id', 'userprofiles_id'],
                        'emptyMessage' => 'Nenhuma marcação pendente.',
                        'revaluedColumns' => [
                                'animais_id' => '\\backend\\models\\AnimalSearch::getNameById(%%)',
                                'userprofiles_id' => '\\backend\\models\\UserprofileSearch::getUserNameById(%%)',
                                'servicos_id' => '\\backend\\models\\ServicoSearch::getServicoNameById(%%)',
                        ],
                        'alternateNamingColumns' => ['horainicio' => 'Início', 'horafim' => 'Fim', 'servicos_id' => 'Serviço', 'animais_id' => 'Animal', 'userprofiles_id' => 'Cliente'],
                ]); ?>
            </div>
        <?php endif; ?>
        <?php if ($usertype == 3): ?> <!-- Receptionist -->
            <div class="row">
                <?php
                echo BigCardWidget::widget([
                        'icon' => 'fa-users',
                        'iconColorClass' => 'icon-purple',
                        'text' => 'Clientes',
                        'value' => $totalClientes,
                        'url' => '/userprofile/index',
                ]);

                echo BigCardWidget::widget([
                        'icon' => 'fa-calendar-check',
                        'iconColorClass' => 'icon-green',
                        'text' => 'Total de Marcações',
                        'value' => $totalmarcacoes,
                        'url' => '/userprofile/index',
                ]);                

                echo BigCardWidget::widget([
                        'icon' => 'fa-stethoscope',
                        'iconColorClass' => 'icon-yellow',
                        'text' => 'Marcações Hoje',
                        'value' => $totalmarcacoesHoje,
                        'url' => '/marcacao/index',
                ]);

                echo BigCardWidget::widget([
                        'icon' => 'fa-calendar',
                        'iconColorClass' => 'icon-red',
                        'text' => 'Marcações Pendentes',
                        'value' => $totalmarcacoesPendentes,
                        'url' => '/marcacao/index',
                ]);
                ?>
            </div>
            <!-- Marcacões de Hoje -->
            <div class=".col-md-12">
                <?php
                echo TableWidget::widget([
                        'title' => 'Marcações',
                        'content' => $marcacoesHoje,
                        'columns' => ['data','estado','horainicio', 'horafim', 'tipo', 'animais_id', 'userprofiles_id'],
                        'emptyMessage' => 'Nenhuma marcação para hoje.',
                        'revaluedColumns' => [
                                'animais_id' => '\\backend\\models\\AnimalSearch::getAnimalNameById(%%)',
                                'userprofiles_id' => '\\backend\\models\\UserprofileSearch::getUserNameById(%%)'
                        ],
                        'alternateNamingColumns' => ['horainicio' => 'Início', 'horafim' => 'Fim', 'animais_id' => 'Animal', 'userprofiles_id' => 'Cliente'],
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>
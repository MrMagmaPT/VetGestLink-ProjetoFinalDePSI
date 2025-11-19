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

$totalMarcacoesHoje = $totalMarcacoesHoje ?? 0;
$totalMarcacoesPendentes = $totalMarcacoesPendentes ?? 0;

$totalMedicamentos = $totalMedicamentos ?? 0;
$totalMedicamentosEmStock = $totalMedicamentosEmStock ?? 0;
$totalMedicamentosBaixoStock = $totalMedicamentosBaixoStock ?? 0;
$totalMedicamentosCriticoStock = $totalMedicamentosCriticoStock ?? 0;
$alertasMedicamentosCriticoStock = $alertasMedicamentosCriticoStock ?? [];

$totalCategorias = $totalCategorias ?? 0;
$totalRacas = $totalRacas ?? 0;
$totalEspecies = $totalEspecies ?? 0;
$faturasDoMes = $faturasDoMes ?? 0;
$receitaMensal = $receitaMensal ?? 0;

$ultimasMarcacoes = $ultimasMarcacoes ?? [];
$marcacoesPendentes = $marcacoesPendentes ?? [];

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
                        //dd($alertasMedicamentosCriticoStock);
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
                echo BigCardWidget::widget([
                    'icon' => 'fa-paw',
                    'iconColorClass' => 'bg-green',
                    'text' => 'Animais',
                    'value' => $totalAnimais,
                    'url' => '/animal/index',
                ]);

                echo BigCardWidget::widget([
                    'icon' => 'fa-stethoscope',
                    'iconColorClass' => 'bg-yellow',
                    'text' => 'Marcações',
                    'value' => $totalMarcacoesHoje,
                    'url' => '/marcacao/index',
                ]);
                ?>
            </div>

            <div class="row mt-4">

                <?php
                echo BigCardWidget::widget([
                    'icon' => 'fa-pills',
                    'iconColorClass' => 'icon-purple',
                    'text' => 'Medicamentos',
                    'value' => $totalMedicamentos,
                    'url' => '/medicamento/index',
                ]);

                echo BigCardWidget::widget([
                    'icon' => 'fa-paw',
                    'iconColorClass' => 'bg-navy',
                    'text' => 'Raças',
                    'value' => $totalRacas,
                    'url' => '/raca/index',
                ]);
                ?>
            </div>

            <div class="row mt-4">
                <?php
                echo BigCardWidget::widget([
                    'icon' => 'fa-paw',
                    'iconColorClass' => 'bg-olive',
                    'text' => 'Especies',
                    'value' => $totalEspecies,
                    'url' => '/especie/index',
                ]);
                ?>
            </div>

            <!-- Últimas marcações table -->
            <div class="row mt-4">
                <?php
                echo TableWidget::widget([
                    'title' => 'Marcações',
                    'content' => $marcacoesPendentes,
                    'columns' => ['data','estado','horainicio', 'horafim', 'tipo', 'animais_id', 'userprofiles_id'],
                        'emptyMessage' => 'Nenhuma marcação pendente.',
                        'revaluedColumns' => [
                            'animais_id' => '\\backend\\models\\AnimalSearch::getAnimalNameById(%%)',
                            'userprofiles_id' => '\\backend\\models\\UserprofileSearch::getUserNameById(%%)'
                        ],
                    'alternateNamingColumns' => ['horainicio' => 'Início', 'horafim' => 'Fim', 'animais_id' => 'Animal', 'userprofiles_id' => 'Cliente'],
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
                ?>
                <?php
                    echo BigCardWidget::widget([
                        'icon' => 'fa-stethoscope',
                        'iconColorClass' => 'bg-yellow',
                        'text' => 'Marcações de Hoje',
                        'value' => $totalMarcacoesHoje,
                        'url' => '/marcacao/index',
                    ]);
                ?>
                <?php
                    echo BigCardWidget::widget([
                        'icon' => 'fa-calendar',
                        'iconColorClass' => 'bg-red',
                        'text' => 'Marcações Pendentes',
                        'value' => $totalMarcacoesPendentes,
                        'url' => '/marcacao/index',
                    ]);
                ?>
            </div>

            <!-- Últimas marcações table -->
            <div class="row mt-12">
                <?php
                //dd($marcacoesPendentes);
                    echo TableWidget::widget([
                        'title' => 'Marcações',
                        'content' => $marcacoesPendentes,
                        'columns' => ['data','estado','horainicio', 'horafim', 'tipo', 'animais_id', 'userprofiles_id'],
                        'emptyMessage' => 'Nenhuma marcação pendente.',
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


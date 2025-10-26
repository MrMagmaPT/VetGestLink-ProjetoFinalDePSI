<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard';
?>
<section class="content-header">
    <h1>Dashboard <small>Painel de Controle</small></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?= $totalClientes ?></h3>
                    <p>Clientes</p>
                </div>
                <div class="icon"><i class="fa fa-users"></i></div>
                <?= Html::a('Mais info <i class="fa fa-arrow-circle-right"></i>', ['/userprofiles/index'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= $totalAnimais ?></h3>
                    <p>Animais</p>
                </div>
                <div class="icon"><i class="fa fa-paw"></i></div>
                <?= Html::a('Mais info <i class="fa fa-arrow-circle-right"></i>', ['/animais/index'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= $marcacoesHoje ?></h3>
                    <p>Marcações Hoje</p>
                </div>
                <div class="icon"><i class="fa fa-stethoscope"></i></div>
                <?= Html::a('Mais info <i class="fa fa-arrow-circle-right"></i>', ['/marcacoes/index'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= $marcacoesPendentes ?></h3>
                    <p>Marcações Pendentes</p>
                </div>
                <div class="icon"><i class="fa fa-calendar"></i></div>
                <?= Html::a('Mais info <i class="fa fa-arrow-circle-right"></i>', ['/marcacoes/index'], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Últimas Marcações</h3>
                </div>
                <div class="box-body">
                    <?php if (!empty($ultimasMarcacoes)): ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Data</th>
                                <th>Animal</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($ultimasMarcacoes as $marcacao): ?>
                                <tr>
                                    <td><?= Yii::$app->formatter->asDatetime($marcacao->data, 'dd/MM/yyyy HH:mm') ?></td>
                                    <td><?= Html::encode($marcacao->animais->nome ?? 'N/A') ?></td>
                                    <td><?= Html::encode($marcacao->userprofiles->nome ?? 'N/A') ?></td>
                                    <td><?= Html::encode($marcacao->tipo) ?></td>
                                    <td>
                                            <span class="label label-<?= $marcacao->estado === 'Pendente' ? 'warning' : 'success' ?>">
                                                <?= Html::encode($marcacao->estado) ?>
                                            </span>
                                    </td>
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

        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Estatísticas do Mês</h3>
                </div>
                <div class="box-body">
                    <p><strong>Faturas emitidas:</strong> <?= $faturasDoMes ?></p>
                    <p><strong>Receita mensal:</strong> <?= Yii::$app->formatter->asCurrency($receitaMensal, 'EUR') ?></p>
                    <hr>
                    <p><strong>Total de clientes:</strong> <?= $totalClientes ?></p>
                    <p><strong>Total de animais:</strong> <?= $totalAnimais ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

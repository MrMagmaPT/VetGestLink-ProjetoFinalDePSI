<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var common\models\Marcacao $marcacao */

$this->title = 'Marcação #' . $marcacao->id;
$this->params['breadcrumbs'][] = ['label' => 'Minhas Marcações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="marcacao-view">
    <div class="container py-4">
        <!-- Header com ID e Botões de Ação -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-calendar-check text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h2>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-secondary me-2']) ?>
            </div>
        </div>

        <div class="row">
            <!-- Coluna Esquerda: Info do Animal -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <?php if ($marcacao->animais): ?>
                        <img src="<?= Html::encode($marcacao->animais->getImageUrl()) ?>"
                             alt="<?= Html::encode($marcacao->animais->nome) ?>"
                             class="card-img-top"
                             style="height: 300px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h4 class="mb-3"><?= Html::encode($marcacao->animais->nome) ?></h4>
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge bg-secondary">
                                    <i class="fas fa-dog"></i>
                                    <?= Html::encode($marcacao->animais->especies->nome ?? 'Sem espécie') ?>
                                </span>
                                <?php if ($marcacao->animais->racas): ?>
                                    <span class="badge bg-info">
                                        <?= Html::encode($marcacao->animais->racas->nome) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="badge bg-<?= $marcacao->animais->sexo == 'M' ? 'primary' : 'danger' ?>">
                                    <i class="fas fa-<?= $marcacao->animais->sexo == 'M' ? 'mars' : 'venus' ?>"></i>
                                    <?= $marcacao->animais->sexo == 'M' ? 'Macho' : 'Fêmea' ?>
                                </span>
                                <span class="badge bg-success">
                                    <i class="fas fa-birthday-cake"></i>
                                    <?= $marcacao->animais->getIdade() ?> anos
                                </span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card-body text-center">
                            <span class="text-muted">Sem animal associado</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Coluna Direita: Informações da Marcação -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalhes da Marcação</h5>
                    </div>
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $marcacao,
                            'options' => ['class' => 'table table-hover mb-0'],
                            'attributes' => [
                                [
                                    'attribute' => 'data',
                                    'format' => ['date', 'php:d/m/Y'],
                                    'label' => 'Data',
                                ],
                                [
                                    'attribute' => 'horainicio',
                                    'format' => 'raw',
                                    'label' => 'Hora Início',
                                ],
                                [
                                    'attribute' => 'horafim',
                                    'format' => 'raw',
                                    'label' => 'Hora Fim',
                                ],
                                [
                                    'attribute' => 'servicos_id',
                                    'label' => 'Serviço',
                                    'value' => $marcacao->getServicoNome() ?? 'Não definido',
                                ],
                                [
                                    'attribute' => 'estado',
                                    'label' => 'Estado',
                                    'value' => $marcacao->displayEstado(),
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format' => ['datetime', 'php:d/m/Y H:i'],
                                    'label' => 'Criado em',
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format' => ['datetime', 'php:d/m/Y H:i'],
                                    'label' => 'Atualizado em',
                                ],
                            ]
                        ]) ?>
                    </div>
                </div>

                <!-- Card de Observações -->
                <?php if ($marcacao->diagnostico): ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Diagnóstico</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light mb-0">
                                <?= Html::encode($marcacao->diagnostico) ?>
                            </div>  
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Diagnóstico</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> Nenhum diagnóstico registrado para esta marcação.
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

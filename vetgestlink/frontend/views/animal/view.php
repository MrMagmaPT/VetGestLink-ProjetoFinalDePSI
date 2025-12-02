<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var common\models\Animal $model */
/** @var common\models\Nota $latestNota */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Meus Animais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="animal-view">
    <div class="container py-4">
        
        <!-- Header com Nome e Botões de Ação -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-paw text-primary"></i>
                <?= Html::encode($model->nome) ?>
            </h2>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-secondary me-2']) ?>
            </div>
        </div>

        <div class="row">
            <!-- Coluna Esquerda: Imagem e Info Rápida -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <img src="<?= Html::encode($model->getImageUrl()) ?>"
                         alt="<?= Html::encode($model->nome) ?>"
                         class="card-img-top"
                         style="height: 300px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h4 class="mb-3"><?= Html::encode($model->nome) ?></h4>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-secondary">
                                <i class="fas fa-dog"></i>
                                <?= Html::encode($model->especies->nome ?? 'Sem espécie') ?>
                            </span>
                            <?php if ($model->racas): ?>
                                <span class="badge bg-info">
                                    <?= Html::encode($model->racas->nome) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-<?= $model->sexo == 'M' ? 'primary' : 'danger' ?>">
                                <i class="fas fa-<?= $model->sexo == 'M' ? 'mars' : 'venus' ?>"></i>
                                <?= $model->sexo == 'M' ? 'Macho' : 'Fêmea' ?>
                            </span>
                            <span class="badge bg-success">
                                <i class="fas fa-birthday-cake"></i>
                                <?= $model->getIdade() ?> anos
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Informações Detalhadas -->
            <div class="col-md-8">
                <!-- Card de Informações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações do Animal</h5>
                    </div>
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-hover mb-0'],
                            'attributes' => [
                                [
                                    'attribute' => 'nome',
                                    'label' => 'Nome',
                                    'format' => 'raw',
                                    'value' => '<strong>' . Html::encode($model->nome) . '</strong>',
                                ],
                                [
                                    'attribute' => 'dtanascimento',
                                    'format' => ['date', 'php:d/m/Y'],
                                    'label' => 'Data de Nascimento'
                                ],
                                [
                                    'attribute' => 'peso',
                                    'format' => 'raw',
                                    'value' => '<span class="badge bg-warning">' . $model->peso . ' kg</span>',
                                    'label' => 'Peso'
                                ],
                                [
                                    'attribute' => 'microship',
                                    'format' => 'raw',
                                    'value' => $model->microship == 1 
                                        ? '<span class="badge bg-success"><i class="fas fa-check"></i> Sim</span>' 
                                        : '<span class="badge bg-danger"><i class="fas fa-times"></i> Não</span>',
                                    'label' => 'Microchip'
                                ],
                                [
                                    'attribute' => 'sexo',
                                    'format' => 'raw',
                                    'value' => '<span class="badge bg-' . ($model->sexo == 'M' ? 'primary' : 'danger') . '">' .
                                               '<i class="fas fa-' . ($model->sexo == 'M' ? 'mars' : 'venus') . '"></i> ' .
                                               ($model->sexo == 'M' ? 'Macho' : 'Fêmea') . '</span>',
                                    'label' => 'Sexo'
                                ],
                                [
                                    'attribute' => 'especies_id',
                                    'label' => 'Espécie',
                                    'value' => $model->especies->nome ?? 'Não definido'
                                ],
                                [
                                    'attribute' => 'racas_id',
                                    'label' => 'Raça',
                                    'value' => $model->racas->nome ?? 'Não definido'
                                ],
                            ]
                        ]) ?>
                    </div>
                </div>

                <!-- Card de Última Nota -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Última Nota</h5>
                        <div>
                            <?= Html::a('<i class="fas fa-plus"></i> Nova Nota', ['/nota/create', 'animal_id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                            <?= Html::a('<i class="fas fa-list"></i> Ver Todas', ['/nota/index', 'animal_id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (isset($latestNota) && $latestNota): ?>
                            <div class="alert alert-light mb-0">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong>
                                        <i class="fas fa-user"></i>
                                        <?= Html::encode($latestNota->userprofiles->nomecompleto ?? 'Desconhecido') ?>
                                    </strong>
                                    <small class="text-muted">
                                        <i class="far fa-calendar"></i>
                                        <?= Yii::$app->formatter->asDate($latestNota->created_at, 'php:d/m/Y H:i') ?>
                                    </small>
                                </div>
                                <p class="mb-0"><?= Html::encode($latestNota->nota) ?></p>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i>
                                Nenhuma nota registada para este animal.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


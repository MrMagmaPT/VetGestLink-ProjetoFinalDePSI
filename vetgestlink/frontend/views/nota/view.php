<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var common\models\Nota $model */



$this->title = 'Nota #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nota-view">
    <div class="container py-4">

        <!-- Header com Título e Botão Voltar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-sticky-note text-primary"></i>
                <?= Html::encode($this->title) ?>
            </h2>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index', 'animalId' => $model->animais_id], ['class' => 'btn btn-secondary me-2']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Card de Detalhes da Nota -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalhes da Nota</h5>
                        <div>
                            <?= Html::a('<i class="fas fa-pencil-alt"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm me-2']) ?>
                            <?= Html::a('<i class="fas fa-trash"></i> Excluir', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => 'Tem certeza que deseja excluir esta nota?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-hover mb-0'],
                            'attributes' => [
                                [
                                    'attribute' => 'userprofiles_id',
                                    'label' => 'Criado por',
                                    'value' => $model->userprofiles->nomecompleto ?? 'Desconhecido',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'label' => 'Data de Criação',
                                    'format' => ['date', 'php:d/m/Y'],
                                ],
                                [
                                    'attribute' => 'nota',
                                    'label' => 'Nota',
                                    'format' => 'ntext',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
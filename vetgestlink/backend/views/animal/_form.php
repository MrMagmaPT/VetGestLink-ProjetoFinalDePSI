<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\widgets\FlashMessages;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $especiesList */
/** @var array $racasList */
/** @var array $userprofilesList */
?>

<div class="animal-form">
    <?php $form = ActiveForm::begin([
            'id' => 'form-signup',
            'options' => [
                'enctype' => 'multipart/form-data'
            ],
            // Erro em vermelho abaixo do campo
            'fieldConfig' => [
                'template' => "{label}\n{input}\n<div class=\"text-danger\">{error}</div>",
            ],
    ]); ?>

    <div class="row">
        <!-- Coluna Esquerda - Formulário -->
        <div class="col-md-8">
            <!-- Card Informações Básicas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-primary"></i>
                        Informações Básicas
                    </h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'nome')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Nome do animal'
                    ]) ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'dtanascimento')->input('date', [
                                'class' => 'form-control',
                                'max' => date('Y-m-d')
                            ])->label('Data de Nascimento') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'peso')->textInput([
                                'type' => 'number',
                                'step' => '0.01',
                                'placeholder' => 'Peso em kg',
                                'class' => 'form-control text-end'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'sexo')->dropDownList(
                                ['M' => 'Macho', 'F' => 'Fêmea'],
                                ['prompt' => 'Selecione o sexo', 'class' => 'form-control']
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'microship')->dropDownList(
                                [1 => 'Sim', 0 => 'Não'],
                                ['prompt' => 'Tem microship?', 'class' => 'form-control']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Classificação -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-paw text-info"></i>
                        Classificação
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'especies_id')->widget(Select2::classname(), [
                                'data' => $especiesList,
                                'options' => ['placeholder' => 'Selecione uma espécie...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Espécie') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'racas_id')->widget(Select2::classname(), [
                                'data' => $racasList,
                                'options' => ['placeholder' => 'Selecione uma raça...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Raça') ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'userprofiles_id')->widget(Select2::classname(), [
                        'data' => $userprofilesList,
                        'options' => ['placeholder' => 'Selecione o dono...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Dono') ?>
                </div>
            </div>

            <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>
        </div>

        <!-- Coluna Direita - Foto e Ações -->
        <div class="col-md-4">
            <!-- Card Fotografia -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-camera text-success"></i>
                        Fotografia
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                        <img id="animal-image-preview" src="<?= $model->getImageUrl() ?>" alt="Foto do animal" class="img-thumbnail rounded" style="max-width: 100%; height: auto; max-height: 250px;<?= $model->getImageUrl() ? '' : 'display:none;' ?>" />
                        <?php if (!$model->getImageUrl()): ?>
                            <i class="fas fa-dog text-muted" style="font-size: 120px;"></i>
                        <?php endif; ?>
                    </div>
                    <?= $form->field($model, 'imageFile')->fileInput([
                        'accept' => 'image/*',
                        'class' => 'form-control',
                        'id' => 'animal-imagefile',
                        'data-image-preview' => 'animal-image-preview',
                    ])->label('Nova Fotografia')->hint('<small class="text-muted"><i class="fas fa-info-circle"></i> Formatos aceites: JPG, PNG (opcional)</small>') ?>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Formatos: JPG, PNG, GIF (máx. 2MB)
                    </small>
            </div>
            </div>

            <!-- Card Ações -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cog text-secondary"></i>
                        Ações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save"></i> Guardar',
                            ['class' => 'btn btn-success btn-md']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-times"></i> Cancelar',
                            ['index'],
                            ['class' => 'btn btn-secondary btn-md']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

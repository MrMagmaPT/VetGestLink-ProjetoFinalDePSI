<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Especie;
use common\models\Userprofile;
use common\models\Raca;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2

/** @var yii\web\View $this */
/** @var common\models\Animal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="animal-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

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
                                'data' => \backend\models\EspecieSearch::getEspeciesList(),
                                'options' => ['placeholder' => 'Selecione uma espécie...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Espécie') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'racas_id')->widget(Select2::classname(), [
                                'data' => \backend\models\RacaSearch::getRacasList(),
                                'options' => ['placeholder' => 'Selecione uma raça...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Raça') ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'userprofiles_id')->widget(Select2::classname(), [
                        'data' => \backend\models\UserprofileSearch::getActiveOwnersList(),
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
                    <div class="mb-3">
                        <img src="<?= $model->getImageUrl() ?>" 
                             alt="Foto do animal" 
                             id="animal-image-preview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 250px; object-fit: cover;<?= $model->getImageUrl() ? '' : 'display:none;' ?>" />
                    </div>
                    <?= $form->field($model, 'imageFile')->fileInput([
                        'accept' => 'image/*',
                        'class' => 'form-control',
                        'id' => 'animal-imagefile',
                        'data-image-preview' => 'animal-image-preview',
                    ])->label('Nova Fotografia')->hint('<small class="text-muted"><i class="fas fa-info-circle"></i> Formatos aceites: JPG, PNG (opcional)</small>') ?>
                </div>
            </div>

            <!-- Card Ações -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks text-secondary"></i>
                        Ações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <?= Html::submitButton(
                            '<i class="fas fa-save"></i> Guardar',
                            ['class' => 'btn btn-success btn-md me-3']
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

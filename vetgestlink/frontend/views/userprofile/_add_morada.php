<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Morada $model */
/** @var int $profileId */

$this->title = $model->isNewRecord ? 'Adicionar Morada' : 'Editar Morada';
$this->params['breadcrumbs'][] = ['label' => 'Perfil', 'url' => ['userprofile/view']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <!-- Header -->
                <div class="card-header text-white py-4" style="background: linear-gradient(90deg, rgb(76, 184, 138) 0%, rgb(148, 226, 182) 100%); border-radius: 1rem 1rem 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">
                                <?= $model->isNewRecord ? '<i class="fas fa-plus-circle me-2"></i>Adicionar Morada' : '<i class="fas fa-edit me-2"></i>Editar Morada' ?>
                            </h4>
                            <small class="opacity-90">Preencha os dados da sua morada</small>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4">
                    <?php $action = $model->isNewRecord ? ['morada/create'] : ['morada/update', 'id' => $model->id]; ?>
                    <?php $form = ActiveForm::begin(['action' => $action]); ?>

                    <?= $form->errorSummary($model, ['class' => 'alert alert-danger rounded-3']) ?>
                    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'userprofiles_id')->hiddenInput(['value' => $profileId ?? $model->userprofiles_id])->label(false) ?>

                    <!-- Localização -->
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-location-dot me-2"></i>Localização
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <?= $form->field($model, 'rua')->textInput([
                                    'maxlength' => true, 
                                    'placeholder' => 'Ex: Rua das Flores',
                                    'class' => 'form-control'
                                ])->label(false)->hint(false) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'nporta')->textInput([
                                    'maxlength' => true, 
                                    'placeholder' => 'Nº Porta',
                                    'class' => 'form-control'
                                ])->label(false)->hint(false) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'andar')->textInput([
                                    'maxlength' => true, 
                                    'placeholder' => 'Andar (opcional)',
                                    'class' => 'form-control'
                                ])->label(false)->hint(false) ?>
                            </div>
                            <div class="col-md-8">
                                <?= $form->field($model, 'cidade')->textInput([
                                    'maxlength' => true, 
                                    'placeholder' => 'Ex: Lisboa',
                                    'class' => 'form-control'
                                ])->label(false)->hint(false) ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Código Postal -->
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-envelope me-2"></i>Código Postal
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <?= $form->field($model, 'cdpostal')->textInput([
                                    'maxlength' => true, 
                                    'placeholder' => 'Ex: 1000-001',
                                    'class' => 'form-control'
                                ])->label(false)->hint(false) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'localidade')->textInput([
                                    'maxlength' => true, 
                                    'placeholder' => 'Localidade',
                                    'class' => 'form-control'
                                ])->label(false)->hint(false) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'cxpostal')->textInput([
                                    'maxlength' => true, 
                                    'placeholder' => 'Cx Postal (opcional)',
                                    'class' => 'form-control'
                                ])->label(false)->hint(false) ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Morada Principal -->
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-star me-2"></i>Preferências
                        </h5>
                        <div class="d-flex align-items-start">
                            <?= Html::activeCheckbox($model, 'principal', [
                                'class' => 'form-check-input mt-1 me-2',
                                'id' => 'morada-principal-check',
                                'uncheck' => '0',
                                'label' => false
                            ]) ?>
                            <label class="form-check-label" for="morada-principal-check" style="cursor: pointer;">
                                <i class="fas fa-home text-success me-2"></i>
                                <strong>Definir como Morada Principal</strong>
                                <br><small class="text-muted">Esta será a morada utilizada por padrão</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center py-3" style="border-radius: 0 0 1rem 1rem;">
                    <?= Html::a('<i class="fas fa-arrow-left me-2"></i>Cancelar', ['userprofile/update'], [
                        'class' => 'btn btn-outline-secondary rounded-pill px-4'
                    ]) ?>
                    <?= Html::submitButton(
                        $model->isNewRecord 
                            ? '<i class="fas fa-plus-circle me-2"></i>Criar Morada' 
                            : '<i class="fas fa-save me-2"></i>Guardar Alterações',
                        ['class' => 'btn btn-success rounded-pill px-4', 'form' => $form->id]
                    ) ?>
                </div>

                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

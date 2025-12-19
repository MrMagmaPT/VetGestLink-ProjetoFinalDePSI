<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Morada $model */
/** @var int $profileId */


$this->title = 'Adicionar Morada';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-4">
    <div class="col-md-6 mx-auto">
        <div class="card p-3">
            <?php $action = $model->isNewRecord ? ['morada/create'] : ['morada/update', 'id' => $model->id]; ?>
            <?php $form = ActiveForm::begin(['action' => $action]); ?>

            <h5 class="mb-3"><?= $model->id ? 'Editar Morada' : 'Adicionar Morada' ?></h5>
            <?= $form->errorSummary($model) ?>
            <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'userprofiles_id')->hiddenInput(['value' => $profileId ?? $model->userprofiles_id])->label(false) ?>

            <?= $form->field($model, 'rua')->textInput(['maxlength' => true, 'placeholder' => 'Nome da rua']) ?>
            <?= $form->field($model, 'nporta')->textInput(['maxlength' => true, 'placeholder' =>'Nº']) ?>
            <?= $form->field($model, 'andar')->textInput(['maxlength' => true, 'placeholder' =>'Andar']) ?>
            <?= $form->field($model, 'cdpostal')->textInput(['maxlength' => true, 'placeholder' =>'Código Postal']) ?>
            <?= $form->field($model, 'localidade')->textInput(['maxlength' => true, 'placeholder' =>'Localidade']) ?>
            <?= $form->field($model, 'cidade')->textInput(['maxlength' => true, 'placeholder' =>'Cidade']) ?>
            <?= $form->field($model, 'cxpostal')->textInput(['maxlength' => true, 'placeholder' =>'Caixa Postal']) ?>

            <div class="d-flex justify-content-end gap-2">
                <?= Html::a('Cancelar', ['userprofile/update'], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::submitButton(
                    '<i class="fas fa-save me-2"></i>' . ($model->isNewRecord ? 'Criar Morada' : 'Guardar Alterações'),
                    ['class' => 'btn btn-success btn-md']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

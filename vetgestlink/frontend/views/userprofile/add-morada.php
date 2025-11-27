<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Morada $model */
$this->title = 'Adicionar Morada';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-4">
    <div class="col-md-6 mx-auto">
        <div class="card p-3">
            <?php $action = isset($model->id) ? ['userprofile/add-morada', 'id' => $model->id] : ['userprofile/add-morada']; ?>
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
                <?= Html::submitButton('Adicionar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

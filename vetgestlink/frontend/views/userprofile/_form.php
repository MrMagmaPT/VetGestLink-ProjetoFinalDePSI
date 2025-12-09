<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var \yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var array $moradas */

$moradas = $moradas ?? ($model->moradas ?? []);

// URL da imagem (o método getImageUrl do modelo trata do default)
$avatarUrl = $model->getImageUrl();

$form = ActiveForm::begin([
    'id' => 'userprofile-form',
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>

<div class="userprofile-form">
    <div class="row mb-3">
        <div class="col-auto">
            <?= Html::img($avatarUrl, ['id' => 'userprofile-image-preview', 'style' => 'width:80px;height:80px;object-fit:cover;border-radius:50%;border:1px solid #ddd;']) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'imageFile')->fileInput([
                'accept' => 'image/png, image/jpeg',
                'id' => 'userprofile-imagefile',
                'data-image-preview' => 'userprofile-image-preview',
            ])->label('Fotografia de Perfil (opcional)') ?>
            <div id="imageFile-client-error" class="text-danger small" style="display:none;margin-top:.25rem;"></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6"><?= $form->field($model, 'nomecompleto')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'telemovel')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'dtanascimento')->input('date') ?></div>
    </div>
    <hr class="my-4 ">

    <?php Pjax::begin(['id' => 'pjax-moradas', 'timeout' => 5000]); ?>
        <?= $this->render('_moradas_list', ['moradas' => $moradas]) ?>
    <?php Pjax::end(); ?>

    <div class="mb-3">
        <?= Html::a('+ Adicionar Morada', ['userprofile/adicionar-morada'], ['class' => 'btn btn-sm btn-success']) ?>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <?= Html::a('Cancelar', ['/userprofile/view'], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJsFile(
    Yii::getAlias('@web/js/image-preview.js'),
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>


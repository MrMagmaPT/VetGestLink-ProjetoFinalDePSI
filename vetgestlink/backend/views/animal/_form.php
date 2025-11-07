<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Especie;
use common\models\Userprofile;
use common\models\Raca;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="animais-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dtanascimento')->input('date')->label('Data de nascimento') ?>

    <?= $form->field($model, 'imageFile')->fileInput()->label('Fotografia') ?>

    <?= $form->field($model, 'peso')->textInput() ?>

    <?= $form->field($model, 'microship')->dropDownList(
            [1 => 'Sim', 0 => 'Não'],
            ['prompt' => 'Tem microship?']
    ) ?>

    <?= $form->field($model, 'sexo')->dropDownList(
            ['M' => 'Macho', 'F' => 'Fêmea'],
            ['prompt' => 'Selecione o sexo']
    ) ?>

    <?= $form->field($model, 'especies_id')->dropDownList(
            ArrayHelper::map(
                    Especie::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
                    'id',
                    'nome'
            ),
            ['prompt' => 'Selecione uma espécie']
    )->label('Espécie') ?>

    <?= $form->field($model, 'racas_id')->dropDownList(
            ArrayHelper::map(
                    Raca::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
                    'id',
                    'nome'
            ),
            ['prompt' => 'Selecione uma raça']
    )->label('Raça') ?>

    <?= $form->field($model, 'userprofiles_id')->dropDownList(
            ArrayHelper::map(
                    Userprofile::find()->orderBy(['nomecompleto' => SORT_ASC])->all(),
                    'id',
                    'nomecompleto'
            ),
            ['prompt' => 'Selecione um dono']
    )->label('Dono') ?>


    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

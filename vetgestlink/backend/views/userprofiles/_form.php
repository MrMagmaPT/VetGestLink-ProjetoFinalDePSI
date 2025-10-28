<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Userprofiles $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="userprofiles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomecompleto')->textInput(['maxlength' => true])->label('Nome completo') ?>

    <?= $form->field($model, 'telemovel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dtanascimento')->input('date')->label('Data de nascimento') ?>

    <?= $form->field($model, 'user_id')->dropDownList(
            \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username'),
            ['prompt' => 'Selecione um utilizador']
    ) ?>

    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

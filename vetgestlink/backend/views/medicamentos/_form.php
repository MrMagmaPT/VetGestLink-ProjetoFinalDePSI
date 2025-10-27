<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Medicamentos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="medicamentos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <?= $form->field($model, 'categorias_id')->dropDownList(
            \yii\helpers\ArrayHelper::map(\common\models\Categorias::find()->all(), 'id', 'nome'),
            ['prompt' => 'Selecione uma categoria']
    ) ?>


    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Especies;


/** @var yii\web\View $this */
/** @var common\models\Racas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="racas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'especies_id')->dropDownList(
            ArrayHelper::map(
                    Especies::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
                    'id',
                    'nome'
            ),
            ['prompt' => 'Selecione uma espécie']
    )->label('Espécie') ?>

    <?= $form->field($model, 'eliminado')->hiddenInput(['value' => 0])->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

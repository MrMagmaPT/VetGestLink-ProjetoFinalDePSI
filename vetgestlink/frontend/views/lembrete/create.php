<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Lembrete $model */

$this->title = 'Create Lembrete';
$this->params['breadcrumbs'][] = ['label' => 'Lembretes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lembrete-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

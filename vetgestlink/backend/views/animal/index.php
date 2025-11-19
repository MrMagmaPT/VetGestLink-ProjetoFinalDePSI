<?php

use common\models\Animal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AnimalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Animal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animais-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Animal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            [
                'attribute' => 'dtanascimento',
                'label' => 'Data de Nascimento',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'peso',
            [
                'attribute' => 'microship',
                'label' => 'Microchip',
                'value' => function($model) {
                    return $model->microship ? 'Sim' : 'Não';
                },
                'filter' => [0 => 'Não', 1 => 'Sim'],
            ],
            [
                'attribute' => 'sexo',
                'filter' => ['M' => 'Macho', 'F' => 'Fêmea'],
            ],
            [
                'attribute' => 'especies_id',
                'label' => 'Espécie',
                'value' => function($model) {
                    return $model->especies->nome ?? '-';
                },
            ],
            [
                'attribute' => 'userprofiles_id',
                'label' => 'Proprietário',
                'value' => function($model) {
                    return $model->userprofiles->nomecompleto ?? '-';
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Animal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

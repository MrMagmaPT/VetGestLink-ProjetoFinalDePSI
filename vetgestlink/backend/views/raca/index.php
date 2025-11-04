<?php

use common\models\Raca;
use common\models\Especie;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var backend\models\RacaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Raca';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="racas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Raca', ['create'], ['class' => 'btn btn-success']) ?>
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
                    'attribute' => 'especies_id',
                    'value' => function($model) {
                        return $model->especies->nome ?? '-';
                    },
                    'filter' => ArrayHelper::map(Especie::find()->where(['eliminado' => 0])->orderBy('nome')->all(), 'id', 'nome'),
                    'label' => 'Espécie',
            ],
            [
                    'attribute' => 'eliminado',
                    'value' => function($model) {
                        return $model->eliminado == 1 ? 'Sim' : 'Não';
                    },
                    'filter' => [0 => 'Não', 1 => 'Sim'],
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Raca $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

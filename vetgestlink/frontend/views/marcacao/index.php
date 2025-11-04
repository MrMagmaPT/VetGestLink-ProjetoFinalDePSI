<?php

use common\models\Marcacao;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Marcacao';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marcacoes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Marcacao', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'data',
            'horainicio',
            'horafim',
            'created_at',
            //'updated_at',
            //'diagnostico',
            //'preco',
            //'estado',
            //'tipo',
            //'animais_id',
            //'userprofiles_id',
            //'eliminado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Marcacao $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

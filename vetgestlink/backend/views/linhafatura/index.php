<?php

use common\models\Linhafatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\LinhafaturaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Linhafaturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linhafatura-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Linhafatura', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'total',
            'quantidade',
            'vendidoemconsulta',
            'faturas_id',
            //'servicos_id',
            //'medicamentos_id',
            //'marcacoes_id',
            //'eliminado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Linhafatura $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

<?php

use common\models\Marcacoes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\MarcacoesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Marcacoes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marcacoes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Marcacoes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'urlCreator' => function ($action, Marcacoes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

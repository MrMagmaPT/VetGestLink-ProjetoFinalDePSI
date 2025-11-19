<?php

use common\models\Nota;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\NotaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Notas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Criar Nota', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'nota',
                'label' => 'Nota',
                'format' => 'ntext',
                'value' => function($model) {
                    return mb_strimwidth($model->nota, 0, 100, '...');
                },
            ],
            [
                'attribute' => 'animais_id',
                'label' => 'Animal',
                'value' => function($model) {
                    return $model->animais ? $model->animais->nome : '-';
                },
            ],
            [
                'attribute' => 'userprofiles_id',
                'label' => 'Autor',
                'value' => function($model) {
                    return $model->userprofiles ? $model->userprofiles->nomecompleto : '-';
                },
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Criado em',
                'format' => ['datetime', 'php:d/m/Y H:i'],
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Nota $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

</div>


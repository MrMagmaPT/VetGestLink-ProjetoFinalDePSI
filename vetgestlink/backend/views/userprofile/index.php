<?php

use common\models\Userprofile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\UserprofileSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Userprofile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userprofiles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Userprofile', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'nomecompleto',
                    'nif',
                    'telemovel',
                    //user id remover dps na entrega
                    'user_id',
                    [
                            'attribute' => 'morada_rua',
                            'label' => 'Rua',
                            'value' => function($model) {
                                $morada = $model->getMoradas()->one();
                                return $morada ? $morada->rua : '-';
                            },
                    ],
                    [
                            'attribute' => 'morada_nporta',
                            'label' => 'Nº Porta',
                            'value' => function($model) {
                                $morada = $model->getMoradas()->one();
                                return $morada ? $morada->nporta : '-';
                            },
                    ],
                    [
                            'attribute' => 'morada_cdpostal',
                            'label' => 'Código Postal',
                            'value' => function($model) {
                                $morada = $model->getMoradas()->one();
                                return $morada ? $morada->cdpostal : '-';
                            },
                    ],
                    [
                            'attribute' => 'morada_cidade',
                            'label' => 'Cidade',
                            'value' => function($model) {
                                $morada = $model->getMoradas()->one();
                                return $morada ? $morada->cidade : '-';
                            },
                    ],
                    [
                            'attribute' => 'morada_localidade',
                            'label' => 'Localidade',
                            'value' => function($model) {
                                $morada = $model->getMoradas()->one();
                                return $morada ? $morada->localidade : '-';
                            },
                    ],
                    [
                            'attribute' => 'eliminado',
                            'label' => 'Eliminado',
                            'value' => function($model) {
                                return $model->eliminado == 1 ? 'Sim' : 'Não';
                            },
                            'filter' => [
                                    0 => 'Não',
                                    1 => 'Sim',
                            ],
                    ],
                    [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Userprofile $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                    ],
            ],
    ]); ?>




</div>

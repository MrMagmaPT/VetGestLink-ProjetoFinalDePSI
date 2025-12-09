<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fatura;

/**
 * FaturaSearch represents the model behind the search form of `common\models\Fatura`.
 */
class FaturaSearch extends Fatura
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'estado', 'metodospagamentos_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['total'], 'number'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Fatura::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'total' => $this->total,
            'estado' => $this->estado,
            'metodospagamentos_id' => $this->metodospagamentos_id,
            'userprofiles_id' => $this->userprofiles_id,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
    /**
     * Retorna lista [id => texto] de faturas para Select2
     */
    public function getFaturasList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Fatura::find()->orderBy(['id' => SORT_DESC])->all(),
            'id',
            function($model) {
                return 'Fatura #' . $model->id . ' - ' . number_format($model->total, 2, ',', '.') . '€';
            }
        );
    }
}

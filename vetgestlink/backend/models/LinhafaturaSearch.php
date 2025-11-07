<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Linhafatura;

/**
 * LinhafaturaSearch represents the model behind the search form of `common\models\Linhafatura`.
 */
class LinhafaturaSearch extends Linhafatura
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quantidade', 'vendidoemconsulta', 'faturas_id', 'medicamentos_id', 'marcacoes_id', 'eliminado'], 'integer'],
            [['total'], 'number'],
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
        $query = Linhafatura::find()->with(['fatura', 'medicamentos', 'marcacao']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
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
            'quantidade' => $this->quantidade,
            'vendidoemconsulta' => $this->vendidoemconsulta,
            'faturas_id' => $this->faturas_id,
            'medicamentos_id' => $this->medicamentos_id,
            'marcacoes_id' => $this->marcacoes_id,
            'eliminado' => $this->eliminado,
        ]);

        return $dataProvider;
    }
}


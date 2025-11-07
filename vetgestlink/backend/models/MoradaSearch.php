<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Morada;

/**
 * MoradaSearch represents the model behind the search form of `common\models\Morada`.
 */
class MoradaSearch extends Morada
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'principal', 'userprofiles_id', 'eliminado'], 'integer'],
            [['rua', 'nporta', 'andar', 'cdpostal', 'cidade', 'cxpostal', 'localidade'], 'safe'],
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
        $query = Morada::find()->with(['userprofiles']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'principal' => SORT_DESC,
                    'cidade' => SORT_ASC,
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
            'principal' => $this->principal,
            'userprofiles_id' => $this->userprofiles_id,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'rua', $this->rua])
            ->andFilterWhere(['like', 'nporta', $this->nporta])
            ->andFilterWhere(['like', 'andar', $this->andar])
            ->andFilterWhere(['like', 'cdpostal', $this->cdpostal])
            ->andFilterWhere(['like', 'cidade', $this->cidade])
            ->andFilterWhere(['like', 'cxpostal', $this->cxpostal])
            ->andFilterWhere(['like', 'localidade', $this->localidade]);

        return $dataProvider;
    }
}


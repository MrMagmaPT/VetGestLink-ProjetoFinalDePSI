<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Marcacoes;

/**
 * MarcacoesSearch represents the model behind the search form of `common\models\Marcacoes`.
 */
class MarcacoesSearch extends Marcacoes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'animais_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['data', 'horainicio', 'horafim', 'created_at', 'updated_at', 'diagnostico', 'estado', 'tipo'], 'safe'],
            [['preco'], 'number'],
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
        $query = Marcacoes::find();

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
            'data' => $this->data,
            'horainicio' => $this->horainicio,
            'horafim' => $this->horafim,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'preco' => $this->preco,
            'animais_id' => $this->animais_id,
            'userprofiles_id' => $this->userprofiles_id,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'diagnostico', $this->diagnostico])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}

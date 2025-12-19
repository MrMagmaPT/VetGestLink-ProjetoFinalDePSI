<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lembrete;

/**
 * LembreteSearch represents the model behind the search form of `common\models\Lembrete`.
 */
class LembreteSearch extends Lembrete
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userprofiles_id'], 'integer'],
            [['descricao', 'created_at', 'updated_at'], 'safe'],
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
        $query = Lembrete::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'userprofiles_id' => $this->userprofiles_id,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }


    /**
     * Retorna todos os lembretes de um usuário, ordenados por data de criação desc.
     * @param int $userId
     * @return Lembrete[]
     */
    public static function getByUserId($userId)
    {
        return Lembrete::find()
            ->where(['userprofiles_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }
}
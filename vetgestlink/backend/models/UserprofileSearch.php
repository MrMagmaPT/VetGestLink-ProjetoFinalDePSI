<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Userprofile;

/**
 * UserprofileSearch represents the model behind the search form of `common\models\Userprofile`.
 */
class UserprofileSearch extends Userprofile
{
    // Atributos públicos para pesquisa de moradas
    public $morada_rua;
    public $morada_nporta;
    public $morada_andar;
    public $morada_cdpostal;
    public $morada_cidade;
    public $morada_localidade;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'eliminado'], 'integer'],
            [['nif', 'telemovel', 'morada_rua', 'morada_nporta', 'morada_andar', 'morada_cdpostal', 'morada_cidade', 'morada_localidade'], 'safe'],
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
        $query = Userprofile::find()
            ->joinWith(['moradas']); // Join com a tabela de moradas

        // add conditions that should always apply her

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Configurar ordenação para campos de moradas
        $dataProvider->sort->attributes['morada_rua'] = [
            'asc' => ['moradas.rua' => SORT_ASC],
            'desc' => ['moradas.rua' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['morada_nporta'] = [
            'asc' => ['moradas.nporta' => SORT_ASC],
            'desc' => ['moradas.nporta' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['morada_cdpostal'] = [
            'asc' => ['moradas.cdpostal' => SORT_ASC],
            'desc' => ['moradas.cdpostal' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['morada_cidade'] = [
            'asc' => ['moradas.cidade' => SORT_ASC],
            'desc' => ['moradas.cidade' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['morada_localidade'] = [
            'asc' => ['moradas.localidade' => SORT_ASC],
            'desc' => ['moradas.localidade' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'telemovel', $this->telemovel])
            ->andFilterWhere(['like', 'moradas.rua', $this->morada_rua])
            ->andFilterWhere(['like', 'moradas.nporta', $this->morada_nporta])
            ->andFilterWhere(['like', 'moradas.andar', $this->morada_andar])
            ->andFilterWhere(['like', 'moradas.cdpostal', $this->morada_cdpostal])
            ->andFilterWhere(['like', 'moradas.cidade', $this->morada_cidade])
            ->andFilterWhere(['like', 'moradas.localidade', $this->morada_localidade]);

        return $dataProvider;
    }

    public static function getUserNameById($id)
    {
        $userprofile = Userprofile::findOne($id);
        if ($userprofile && $userprofile->user) {
            return $userprofile->user->username;
        }
        return null;
    }
}

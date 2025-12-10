<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Especie;

/**
 * EspecieSearch represents the model behind the search form of `common\models\Especie`.
 */
class EspecieSearch extends Especie
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'eliminado'], 'integer'],
            [['nome'], 'safe'],
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
        $query = Especie::find()
            ->with(['racas']); // Eager loading

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
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome]);

        return $dataProvider;
    }

    /**
     * Lista completa [id => nome] de espécies
     */
    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Especie::find()->orderBy('nome')->all(),
            'id', 'nome'
        );
    }

    /**
     * Lista apenas espécies ativas [id => nome]
     */
    public static function getActiveList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Especie::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
            'id', 'nome'
        );
    }

    /**
     * Buscar nome da espécie por ID
     */
    public static function getNameById($id)
    {
        $especie = Especie::findOne($id);
        return $especie ? $especie->nome : null;
    }

    /**
     * @deprecated Use getActiveList() instead
     */
    public static function getEspeciesList()
    {
        return static::getActiveList();
    }
}

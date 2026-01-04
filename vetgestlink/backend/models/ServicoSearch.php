<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Servico;

/**
 * ServicoSearch represents the model behind the search form of `common\models\Servico`.
 */
class ServicoSearch extends Servico
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'eliminado'], 'integer'],
            [['nome'], 'safe'],
            [['valor'], 'number'],
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
        $query = Servico::find();

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
            'valor' => $this->valor,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome]);

        return $dataProvider;
    }

    /**
     * Lista completa [id => nome] de serviços
     */
    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Servico::find()->orderBy('nome')->all(),
            'id', 'nome'
        );
    }

    /**
     * Lista apenas serviços ativos [id => nome]
     */
    public static function getActiveList()
    {
        return \yii\helpers\ArrayHelper::map(
            Servico::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
            'id', 'nome'
        );
    }

    /**
     * Buscar nome do serviço por ID
     */
    public static function getNameById($id)
    {
        $servico = Servico::findOne($id);
        return $servico ? $servico->nome : null;
    }

    /**
     * @deprecated Use getNameById() instead
     */
    public static function getServicoNameById($id)
    {
        return static::getNameById($id);
    }


}

<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Animal;

/**
 * AnimalSearch represents the model behind the search form of `common\models\Animal`.
 */
class AnimalSearch extends Animal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'microship', 'especies_id', 'userprofiles_id', 'racas_id', 'eliminado'], 'integer'],
            [['nome', 'dtanascimento', 'sexo'], 'safe'],
            [['peso'], 'number'],
        ];
    }

        /**
         * Retorna lista de animais ativos para Select2
         */
        public static function getActiveAnimalsList()
        {
            return \yii\helpers\ArrayHelper::map(
                Animal::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
                'id',
                'nome'
            );
        }

        /**
         * Retorna lista de todos os animais para Select2
         */
        public static function getAnimaisList()
        {
            return \yii\helpers\ArrayHelper::map(
                Animal::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
                'id',
                'nome'
            );
        }

        /**
         * Retorna lista de donos ativos com animais para Select2
         */
        public static function getActiveOwnersList()
        {
            $owners = \common\models\Userprofile::find()
                ->where(['eliminado' => 0])
                ->andWhere(['in', 'id',
                    (new \yii\db\Query())
                        ->select('userprofiles_id')
                        ->from('animais')
                        ->where(['eliminado' => 0])
                        ->groupBy('userprofiles_id')
                ])
                ->orderBy('nomecompleto')
                ->all();
            return \yii\helpers\ArrayHelper::map($owners, 'id', 'nomecompleto');
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
        $query = Animal::find();

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
            'dtanascimento' => $this->dtanascimento,
            'peso' => $this->peso,
            'microship' => $this->microship,
            'especies_id' => $this->especies_id,
            'userprofiles_id' => $this->userprofiles_id,
            'racas_id' => $this->racas_id,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'sexo', $this->sexo]);

        return $dataProvider;
    }

    public static function getAnimalNameById($id)
    {
        $animal = Animal::findOne($id);
        return $animal ? $animal->nome : null;
    }

    public static  function getAnimaisListTEST() {
        return Animal::find()->where(['eliminado' => 0])->orderBy('nome')->all();
    }
}

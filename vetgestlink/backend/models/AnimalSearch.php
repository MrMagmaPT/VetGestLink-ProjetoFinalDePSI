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
            [['nome', 'dtanascimento', 'sexo', 'created_at', 'updated_at'], 'safe'],
            [['peso'], 'number'],
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
        $query = Animal::find()
            ->with(['especies', 'userprofiles', 'racas']); // Eager loading

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'sexo', $this->sexo]);

        return $dataProvider;
    }

    /**
     * Lista completa [id => nome] de animais
     */
    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Animal::find()->orderBy('nome')->all(),
            'id', 'nome'
        );
    }

    /**
     * Lista apenas animais ativos [id => nome]
     */
    public static function getActiveList()
    {
        return \yii\helpers\ArrayHelper::map(
            Animal::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
            'id', 'nome'
        );
    }

    /**
     * Buscar nome do animal por ID
     * @param int $id
     * @return string|null
     */
    public static function getNameById($id)
    {
        $animal = Animal::findOne($id);
        return $animal ? $animal->nome : null;
    }

    /**
     * Retorna todos os animais de um usuário.
     * @param int $userId
     * @return Animal[]
     */
    public static function getByUserId($userId)
    {
        return Animal::find()
            ->where(['userprofiles_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }
    
    /**
     * Lista de donos que possuem animais ativos [id => nomecompleto]
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
     * Retorna objetos Animal (para casos que precisam do objeto completo)
     */
    public static function getActiveAnimalsObjects()
    {
        return Animal::find()->where(['eliminado' => 0])->orderBy('nome')->all();
    }

    /**
     * Lista de animais para Select2 no index [id => nome]
     */
    public static function getAnimaisListForIndex()
    {
        return \yii\helpers\ArrayHelper::map(
            Animal::find()
                ->select(['id', 'nome'])
                ->where(['eliminado' => 0])
                ->orderBy('nome')
                ->all(),
            'id',
            'nome'
        );
    }

    /**
     * Lista de espécies para Select2 no index [id => nome]
     */
    public static function getEspeciesListForIndex()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Especie::find()
                ->select(['id', 'nome'])
                ->where(['eliminado' => 0])
                ->orderBy('nome')
                ->all(),
            'id',
            'nome'
        );
    }

    /**
     * Lista de donos (clientes) para Select2 no index [id => nomecompleto]
     */
    public static function getDonosListForIndex()
    {
        // Retorna apenas donos que possuem animais não eliminados
        return \yii\helpers\ArrayHelper::map(
            \common\models\Userprofile::find()
                ->where(['userprofiles.eliminado' => 0])
                ->andWhere(['in', 'userprofiles.id',
                    (new \yii\db\Query())
                        ->select('userprofiles_id')
                        ->from('animais')
                        ->where(['eliminado' => 0])
                        ->groupBy('userprofiles_id')
                ])
                ->orderBy('nomecompleto')
                ->all(),
            'id',
            'nomecompleto'
        );
    }

}

<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Nota;

/**
 * NotaSearch represents the model behind the search form of `common\models\Nota`.
 */
class NotaSearch extends Nota
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'animais_id', 'userprofiles_id'], 'integer'],
            [['nota', 'created_at'], 'safe'],
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
        $query = Nota::find()->with(['animais', 'userprofiles']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
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
            'animais_id' => $this->animais_id,
            'userprofiles_id' => $this->userprofiles_id,
        ]);

        $query->andFilterWhere(['like', 'nota', $this->nota])
            ->andFilterWhere(['>=', 'created_at', $this->created_at ? $this->created_at . ' 00:00:00' : null]);

        return $dataProvider;
    }

    /**
     * Lista completa [id => nota] de notas
     */
    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Nota::find()->orderBy(['created_at' => SORT_DESC])->all(),
            'id',
            function($model) {
                return substr($model->nota, 0, 50) . '...';
            }
        );
    }

    /**
     * Buscar nota por ID
     */
    public static function getNoteById($id)
    {
        $nota = Nota::findOne($id);
        return $nota ? $nota->nota : null;
    }

    /**
     * Estatísticas para dashboard
     */
    public static function getTotalCount()
    {
        return Nota::find()->count();
    }

    public static function getRecentCount()
    {
        return Nota::find()->where(['>=', 'created_at', date('Y-m-d H:i:s', strtotime('-7 days'))])->count();
    }
}

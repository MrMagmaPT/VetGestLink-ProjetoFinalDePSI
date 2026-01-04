<?php

namespace backend\models;


use common\models\Userprofile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Marcacao;

/**
 * MarcacaoSearch represents the model behind the search form of `common\models\Marcacao`.
 */
class MarcacaoSearch extends Marcacao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'animais_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['data', 'horainicio', 'horafim', 'created_at', 'updated_at', 'diagnostico', 'estado'], 'safe'],
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
        $query = Marcacao::find()
            ->with(['animais', 'userprofiles']); // Eager loading

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
            'animais_id' => $this->animais_id,
            'userprofiles_id' => $this->userprofiles_id,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'diagnostico', $this->diagnostico])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }

    /**
     * Lista completa [id => descrição] de marcações
     */
    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Marcacao::find()->orderBy(['data' => SORT_DESC])->all(),
            'id',
            function($model) {
                return 'Marcação #' . $model->id . ' - ' . $model->data;
            }
        );
    }

    /**
     * Lista apenas marcações ativas [id => descrição]
     */
    public static function getActiveList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Marcacao::find()
                ->where(['eliminado' => 0])
                ->orderBy(['data' => SORT_DESC])
                ->all(),
            'id',
            function($model) {
                return 'Marcação #' . $model->id . ' - ' . $model->data;
            }
        );
    }

    /**
     * Lista [id => nome] de animais para filtros
     */
    public static function getAnimaisList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Animal::find()->orderBy('nome')->all(),
            'id', 'nome'
        );
    }

    /**
     * Lista [id => nomecompleto] de userprofiles para filtros
     */
    public static function getUserprofilesList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Userprofile::find()->orderBy('nomecompleto')->all(),
            'id', 'nomecompleto'
        );
    }

    /**
     * Retorna todas as marcações de um usuário específico.
     * @param int $userId
     * @return Marcacao[]
     */
    public static function getByUserId($userId)
    {
        return Marcacao::find()->where(['userprofiles_id' => $userId])->all();
    }

    /**
     * Lista [id => descrição] de marcações que ainda não possuem linha de fatura
     * @return array
     */
    public static function getMarcacoesSemFaturaList()
    {
        $marcacoes = Marcacao::find()
            ->joinWith('servicos')
            ->joinWith('animais')
            ->where(['marcacoes.eliminado' => 0])
            ->andWhere(['not in', 'marcacoes.id', 
                \common\models\Linhafatura::find()->select('marcacoes_id')->where(['not', ['marcacoes_id' => null]])
            ])
            ->all();
        
        $list = [];
        foreach ($marcacoes as $marcacao) {
            $servico = $marcacao->servicos ? $marcacao->servicos->nome : 'Serviço';
            $animal = $marcacao->animais ? ' - ' . $marcacao->animais->nome : '';
            $data = $marcacao->data ? ' (' . \Yii::$app->formatter->asDate($marcacao->data, 'php:d/m/Y') . ')' : '';
            $list[$marcacao->id] = $servico . $animal . $data;
        }
        
        return $list;
    }

    /**
     * Lista de datas de marcações para Select2 no index [data => data formatada]
     */
    public static function getDatasListForIndex()
    {
        return \yii\helpers\ArrayHelper::map(
            Marcacao::find()
                ->select(['data'])
                ->distinct()
                ->where(['eliminado' => 0])
                ->orderBy(['data' => SORT_DESC])
                ->all(),
            'data',
            function($model) {
                return \Yii::$app->formatter->asDate($model->data, 'php:d/m/Y');
            }
        );
    }

    /**
     * Lista de animais para Select2 no index [id => nome]
     */
    public static function getAnimaisListForIndex()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Animal::find()
                ->select(['id', 'nome'])
                ->where(['eliminado' => 0])
                ->orderBy('nome')
                ->all(),
            'id',
            'nome'
        );
    }

    /**
     * Lista de veterinários para Select2 no index [id => nomecompleto]
     */
    public static function getVeterinariosListForIndex()
    {
        return \yii\helpers\ArrayHelper::map(
            Userprofile::find()
                ->joinWith(['user'])
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
                ->where([
                    'auth_assignment.item_name' => 'veterinario',
                    'userprofiles.eliminado' => 0
                ])
                ->orderBy('nomecompleto')
                ->all(),
            'id',
            'nomecompleto'
        );
    }

}

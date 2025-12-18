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
    /**
     * Retorna lista [id => nomecompleto] de donos ativos para Select2
     */
    public static function getActiveOwnersList()
    {
        $owners = \common\models\Userprofile::find()
            ->where(['eliminado' => 0])
            ->orderBy('nomecompleto')
            ->all();
        return \yii\helpers\ArrayHelper::map($owners, 'id', 'nomecompleto');
    }
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
            [['nomecompleto','nif', 'telemovel', 'morada_rua', 'morada_nporta', 'morada_andar', 'morada_cdpostal', 'morada_cidade', 'morada_localidade'], 'safe'],
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
            ->joinWith(['moradas'])
            ->with(['moradas']); // Eager loading para evitar queries N+1

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

        $query->andFilterWhere([
            'userprofiles.id' => $this->id,
            'nomecompleto' => $this->nomecompleto,
            'user_id' => $this->user_id,
            'userprofiles.eliminado' => $this->eliminado,
        ]);

        $query
            ->andFilterWhere(['like', 'nif', $this->nif])
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

    /**
     * Lista de NIFs únicos para filtro Select2
     */
    public function getNifList()
    {
        return Userprofile::find()->select(['nif', 'nif'])->where(['eliminado' => 0])->distinct()->indexBy('nif')->column();
    }

    /**
     * Lista de telemóveis únicos para filtro Select2
     */
    public function getTelemovelList()
    {
        return Userprofile::find()->select(['telemovel', 'telemovel'])->where(['eliminado' => 0])->distinct()->indexBy('telemovel')->column();
    }

    /**
     * Lista de cidades únicas para filtro Select2
     */
    public function getCidadeList()
    {
        return \common\models\Morada::find()->select(['cidade', 'cidade'])->distinct()->indexBy('cidade')->column();
    }

    /**
     * Lista de nomes completos únicos para filtro Select2
     */
    public function getNomecompletoList()
    {
        // Retorna array [id => nomecompleto] para pesquisa pelo nome e envio do ID
        return \yii\helpers\ArrayHelper::map(
            Userprofile::find()
                ->select(['id', 'nomecompleto'])
                ->orderBy('nomecompleto')
                ->asArray()
                ->all(),
            'id', 'nomecompleto'
        );
    }

    public static function getUserListByType(string $type, $eliminadoSN = NULL) {
        $where = [
            'auth_assignment.item_name' => $type
        ];

        if ($eliminadoSN != NULL) {
            $where['userprofiles.eliminado'] = $eliminadoSN;
        }

        return Userprofile::find()
            ->joinWith(['user'])
            ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->where($where)
            ->all();
    }

}

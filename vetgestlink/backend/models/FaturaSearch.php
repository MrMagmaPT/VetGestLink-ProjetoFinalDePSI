<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fatura;

/**
 * FaturaSearch represents the model behind the search form of `common\models\Fatura`.
 */
class FaturaSearch extends Fatura
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'estado', 'metodospagamentos_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['total'], 'number'],
            [['created_at'], 'safe'],
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
        $query = Fatura::find()
            ->with(['userprofiles', 'metodospagamentos', 'linhasfaturas']); // Eager loading

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
            'total' => $this->total,
            'estado' => $this->estado,
            'metodospagamentos_id' => $this->metodospagamentos_id,
            'userprofiles_id' => $this->userprofiles_id,
            'eliminado' => $this->eliminado,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
    /**
     * Lista completa [id => descrição] de faturas
     */
    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Fatura::find()->orderBy(['id' => SORT_DESC])->all(),
            'id',
            function($model) {
                return 'Fatura #' . $model->id . ' - ' . number_format($model->total, 2, ',', '.') . '€';
            }
        );
    }

    /**
     * Lista apenas faturas ativas [id => descrição]
     */
    public static function getActiveList()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Fatura::find()
                ->where(['eliminado' => 0])
                ->orderBy(['id' => SORT_DESC])
                ->all(),
            'id',
            function($model) {
                return 'Fatura #' . $model->id . ' - ' . number_format($model->total, 2, ',', '.') . '€';
            }
        );
    }

    /**
     * Estatísticas para dashboard
     */
    public static function getTotalCount()
    {
        return Fatura::find()->count();
    }

    public static function getPaidCount()
    {
        return Fatura::find()->where(['estado' => 1, 'eliminado' => 0])->count();
    }

    public static function getPendingCount()
    {
        return Fatura::find()->where(['estado' => 0, 'eliminado' => 0])->count();
    }

    /**
     * @deprecated Use getList() instead
     */
    public function getFaturasList()
    {
        return static::getList();
    }

    /**
     * Lista de métodos de pagamento ativos [id => nome]
     */
    public static function getMetodosPagamentoAtivos()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Metodopagamento::find()->where(['eliminado' => 0])->orderBy('nome')->all(),
            'id', 'nome'
        );
    }



    /**
     * Retorna todos os lembretes de um usuário, ordenados por data de criação desc.
     * @param int $userId
     * @return Fatura[]
     */
    public static function getByUserId($userId)
    {
        return Fatura::find()
            ->where(['userprofiles_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }

    /**
     * Lista de números de faturas para Select2 no index [id => Nº]
     */
    public static function getFaturasListForIndex()
    {
        return \yii\helpers\ArrayHelper::map(
            Fatura::find()
                ->select(['id'])
                ->orderBy(['id' => SORT_DESC])
                ->all(),
            'id',
            function($model) {
                return '#' . $model->id;
            }
        );
    }

    /**
     * Lista de métodos de pagamento para Select2 no index [id => nome]
     */
    public static function getMetodosPagamentoListForIndex()
    {
        return \yii\helpers\ArrayHelper::map(
            \common\models\Metodopagamento::find()
                ->select(['id', 'nome'])
                ->where(['eliminado' => 0])
                ->orderBy('nome')
                ->all(),
            'id',
            'nome'
        );
    }

    /**
     * Lista de estados para Select2 no index
     */
    public static function getEstadosListForIndex()
    {
        return [
            0 => 'Pendente',
            1 => 'Paga'
        ];
    }

    /**
     * Estatísticas para dashboard - faturas do mês
     */
    public static function getFaturasDoMesCount()
    {
        $inicioMes = strtotime(date('Y-m-01 00:00:00'));
        $fimMes = strtotime(date('Y-m-t 23:59:59'));
        
        return Fatura::find()
            ->where(['between', 'created_at', $inicioMes, $fimMes])
            ->andWhere(['eliminado' => 0])
            ->count();
    }

    /**
     * Receita mensal total
     */
    public static function getReceitaMensal()
    {
        $inicioMes = strtotime(date('Y-m-01 00:00:00'));
        $fimMes = strtotime(date('Y-m-t 23:59:59'));
        
        return Fatura::find()
            ->where(['between', 'created_at', $inicioMes, $fimMes])
            ->andWhere(['eliminado' => 0])
            ->sum('total') ?? 0;
    }

    /**
     * Dados de faturamento dos últimos 12 meses para gráfico
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public static function getDadosFaturamentoAnual()
    {
        $anoAtual = date('Y');
        $nomesMeses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        
        $dadosFaturamento = [];
        $labelsMeses = [];
        
        for ($mes = 1; $mes <= 12; $mes++) {
            $totalMes = Fatura::find()
                ->where(['YEAR(created_at)' => $anoAtual])
                ->andWhere(['MONTH(created_at)' => $mes])
                ->andWhere(['eliminado' => 0, 'estado' => 1]) // Apenas faturas pagas
                ->sum('total') ?? 0;
            
            $dadosFaturamento[] = (float)$totalMes;
            $labelsMeses[] = $nomesMeses[$mes - 1];
        }
        
        return [
            'labels' => $labelsMeses,
            'data' => $dadosFaturamento
        ];
    }}
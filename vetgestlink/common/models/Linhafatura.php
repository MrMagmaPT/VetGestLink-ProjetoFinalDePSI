<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "linhasfaturas".
 *
 * @property int $id
 * @property float $total
 * @property int $quantidade
 * @property int $vendidoemconsulta
 * @property int $faturas_id
 * @property int|null $medicamentos_id
 * @property int|null $servicos_id
 * @property int|null $marcacoes_id
 * @property int $eliminado
 *
 * @property Fatura $fatura
 * @property Marcacao $marcacao
 * @property Medicamento $medicamento
 * @property Servico $servico
 */
class Linhafatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linhasfaturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['medicamentos_id', 'servicos_id', 'marcacoes_id'], 'default', 'value' => null],
            [['quantidade'], 'default', 'value' => 1],
            [['eliminado'], 'default', 'value' => 0],
            [['total', 'faturas_id'], 'required'],
            [['total'], 'number'],
            [['quantidade', 'vendidoemconsulta', 'faturas_id', 'medicamentos_id', 'servicos_id', 'marcacoes_id', 'eliminado'], 'integer'],
            [['faturas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fatura::class, 'targetAttribute' => ['faturas_id' => 'id']],
            [['marcacoes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Marcacao::class, 'targetAttribute' => ['marcacoes_id' => 'id']],
            [['servicos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Servico::class, 'targetAttribute' => ['servicos_id' => 'id']],
            [['medicamentos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medicamento::class, 'targetAttribute' => ['medicamentos_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total' => 'Total',
            'quantidade' => 'Quantidade',
            'vendidoemconsulta' => 'Vendidoemconsulta',
            'faturas_id' => 'Fatura ID',
            'medicamentos_id' => 'Medicamento ID',
            'marcacoes_id' => 'Marcacao ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasOne(Fatura::class, ['id' => 'faturas_id']);
    }

    /**
     * Gets query for [[Marcacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasOne(Marcacao::class, ['id' => 'marcacoes_id']);
    }

    /**
     * Gets query for [[Medicamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicamentos()
    {
        return $this->hasOne(Medicamento::class, ['id' => 'medicamentos_id']);
    }

    /**
     * Gets query for [[Servico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicos()
    {
        return $this->hasOne(Servico::class, ['id' => 'servicos_id']);
    }

    /**
     * Define automaticamente o campo vendidoemconsulta antes de salvar
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Se tem marcação associada, foi vendido em consulta
        if ($this->marcacoes_id !== null) {
            $this->vendidoemconsulta = 1;
        } 
        // Se tem apenas medicamento (sem marcação), não foi vendido em consulta
        elseif ($this->medicamentos_id !== null && $this->marcacoes_id === null) {
            $this->vendidoemconsulta = 0;
        }

        return true;
    }

}

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
 * @property int|null $marcacoes_id
 * @property int $eliminado
 *
 * @property Faturas $faturas
 * @property Marcacoes $marcacoes
 * @property Medicamentos $medicamentos
 */
class Linhasfaturas extends \yii\db\ActiveRecord
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
            [['medicamentos_id', 'marcacoes_id'], 'default', 'value' => null],
            [['quantidade'], 'default', 'value' => 1],
            [['eliminado'], 'default', 'value' => 0],
            [['total', 'faturas_id'], 'required'],
            [['total'], 'number'],
            [['quantidade', 'vendidoemconsulta', 'faturas_id', 'medicamentos_id', 'marcacoes_id', 'eliminado'], 'integer'],
            [['faturas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faturas::class, 'targetAttribute' => ['faturas_id' => 'id']],
            [['marcacoes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Marcacoes::class, 'targetAttribute' => ['marcacoes_id' => 'id']],
            [['medicamentos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medicamentos::class, 'targetAttribute' => ['medicamentos_id' => 'id']],
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
            'faturas_id' => 'Faturas ID',
            'medicamentos_id' => 'Medicamentos ID',
            'marcacoes_id' => 'Marcacoes ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasOne(Faturas::class, ['id' => 'faturas_id']);
    }

    /**
     * Gets query for [[Marcacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasOne(Marcacoes::class, ['id' => 'marcacoes_id']);
    }

    /**
     * Gets query for [[Medicamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicamentos()
    {
        return $this->hasOne(Medicamentos::class, ['id' => 'medicamentos_id']);
    }

}

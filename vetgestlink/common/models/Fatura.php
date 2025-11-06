<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fatura".
 *
 * @property int $id
 * @property float $total
 * @property string $data
 * @property int $estado
 * @property int $metodospagamentos_id
 * @property int $userprofiles_id
 * @property int $eliminado
 *
 * @property Linhafatura[] $linhasfaturas
 * @property Metodopagamento $metodospagamentos
 * @property Userprofile $userprofile
 */
class Fatura extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['total', 'data', 'estado', 'metodospagamentos_id', 'userprofiles_id'], 'required'],
            [['total'], 'number'],
            [['data'], 'safe'],
            [['estado', 'metodospagamentos_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['metodospagamentos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metodopagamento::class, 'targetAttribute' => ['metodospagamentos_id' => 'id']],
            [['userprofiles_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userprofile::class, 'targetAttribute' => ['userprofiles_id' => 'id']],
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
            'data' => 'Data',
            'estado' => 'Estado',
            'metodospagamentos_id' => 'Metodopagamento ID',
            'userprofiles_id' => 'Userprofile ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhafatura::class, ['faturas_id' => 'id']);
    }

    /**
     * Gets query for [[Metodopagamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodospagamentos()
    {
        return $this->hasOne(Metodopagamento::class, ['id' => 'metodospagamentos_id']);
    }

    /**
     * Gets query for [[Userprofile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }




}

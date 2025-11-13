<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faturas".
 *
 * @property int $id
 * @property float $total
 * @property int $estado
 * @property string $created_at
 * @property int $metodospagamentos_id
 * @property int $userprofiles_id
 * @property int $eliminado
 *
 * @property Linhasfatura[] $linhasfaturas
 * @property Metodospagamento $metodospagamentos
 * @property Userprofile $userprofiles
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
            [['total', 'estado', 'created_at', 'metodospagamentos_id', 'userprofiles_id'], 'required'],
            [['total'], 'number'],
            [['estado', 'metodospagamentos_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['created_at'], 'safe'],
            [['metodospagamentos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metodospagamento::class, 'targetAttribute' => ['metodospagamentos_id' => 'id']],
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
            'estado' => 'Estado',
            'created_at' => 'Created At',
            'metodospagamentos_id' => 'Metodospagamentos ID',
            'userprofiles_id' => 'Userprofiles ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Linhasfaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhafatura::class, ['faturas_id' => 'id']);
    }

    /**
     * Gets query for [[Metodospagamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodospagamentos()
    {
        return $this->hasOne(Metodopagamento::class, ['id' => 'metodospagamentos_id']);
    }

    /**
     * Gets query for [[Userprofiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }

}

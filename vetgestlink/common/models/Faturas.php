<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faturas".
 *
 * @property int $id
 * @property float $total
 * @property string $data
 * @property int $estado
 * @property int $metodospagamentos_id
 * @property int $userprofiles_id
 * @property int $eliminado
 *
 * @property Linhasfaturas[] $linhasfaturas
 * @property Metodospagamentos $metodospagamentos
 * @property Userprofiles $userprofiles
 */
class Faturas extends \yii\db\ActiveRecord
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
            [['metodospagamentos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metodospagamentos::class, 'targetAttribute' => ['metodospagamentos_id' => 'id']],
            [['userprofiles_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userprofiles::class, 'targetAttribute' => ['userprofiles_id' => 'id']],
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
        return $this->hasMany(Linhasfaturas::class, ['faturas_id' => 'id']);
    }

    /**
     * Gets query for [[Metodospagamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodospagamentos()
    {
        return $this->hasOne(Metodospagamentos::class, ['id' => 'metodospagamentos_id']);
    }

    /**
     * Gets query for [[Userprofiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofiles::class, ['id' => 'userprofiles_id']);
    }

}

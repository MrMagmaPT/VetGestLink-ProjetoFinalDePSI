<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "fatura".
 *
 * @property int $id
 * @property float $total
 * @property string $created_at
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
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false, // Não tem updated_at
                'value' => new Expression('NOW()'),
            ],
        ];
    }

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
            [['total', 'estado', 'metodospagamentos_id', 'userprofiles_id'], 'required'],
            [['created_at'], 'safe'],
            [['total'], 'number'],
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
            'created_at' => 'Data de Criação',
            'estado' => 'Estado',
            'metodospagamentos_id' => 'Método de Pagamento',
            'userprofiles_id' => 'Cliente',
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

    /**
     * Obter data de criação formatada
     * @param string $format Formato de saída (padrão: 'Y-m-d H:i:s')
     * @return string|null
     */
    public function getCreatedAtFormatted($format = 'Y-m-d H:i:s')
    {
        return $this->created_at ? date($format, $this->created_at) : null;
    }


    /**
     * Obter data da fatura formatada (alias para created_at)
     * @param string $format Formato de saída (padrão: 'd/m/Y')
     * @return string|null
     */
    public function getDataFatura($format = 'd/m/Y')
    {
        return $this->getCreatedAtFormatted($format);
    }
}

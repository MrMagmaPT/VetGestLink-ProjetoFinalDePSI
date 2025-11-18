<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "marcacao".
 *
 * @property int $id
 * @property string $data
 * @property string $horainicio
 * @property string $horafim
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $diagnostico
 * @property string $estado
 * @property int $animais_id
 * @property int $userprofiles_id
 * @property int $eliminado
 *
 * @property Animal $animal
 * @property Linhafatura[] $linhasfaturas
 * @property Userprofile $userprofile
 */
class Marcacao extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTADO_PENDENTE = 'pendente';
    const ESTADO_CANCELADA = 'cancelada';
    const ESTADO_REALIZADA = 'realizada';
    const TIPO_CONSULTA = 'consulta';
    const TIPO_CIRURGIA = 'cirurgia';
    const TIPO_OPERACAO = 'operacao';

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function tableName()
    {
        return 'marcacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['diagnostico'], 'default', 'value' => null],
            [['eliminado'], 'default', 'value' => 0],
            [['data', 'horainicio', 'horafim', 'estado', 'animais_id', 'userprofiles_id'], 'required'],
            [['data', 'horainicio', 'horafim'], 'safe'],
            [['estado'], 'string'],
            [['animais_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['diagnostico'], 'string', 'max' => 500],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['animais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animais_id' => 'id']],
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
            'data' => 'Data',
            'horainicio' => 'Horainicio',
            'horafim' => 'Horafim',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'diagnostico' => 'Diagnostico',
            'estado' => 'Estado',
            'animais_id' => 'Animal ID',
            'userprofiles_id' => 'Userprofile ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Animal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasOne(Animal::class, ['id' => 'animais_id']);
    }

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhafatura::class, ['marcacoes_id' => 'id']);
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
     * column estado ENUM value labels
     * @return string[]
     */
    public static function optsEstado()
    {
        return [
            self::ESTADO_PENDENTE => 'pendente',
            self::ESTADO_CANCELADA => 'cancelada',
            self::ESTADO_REALIZADA => 'realizada',
        ];
    }


    /**
     * @return string
     */
    public function displayEstado()
    {
        return self::optsEstado()[$this->estado];
    }

    /**
     * @return bool
     */
    public function isEstadoPendente()
    {
        return $this->estado === self::ESTADO_PENDENTE;
    }

    public function setEstadoToPendente()
    {
        $this->estado = self::ESTADO_PENDENTE;
    }

    /**
     * @return bool
     */
    public function isEstadoCancelada()
    {
        return $this->estado === self::ESTADO_CANCELADA;
    }

    public function setEstadoToCancelada()
    {
        $this->estado = self::ESTADO_CANCELADA;
    }

    /**
     * @return bool
     */
    public function isEstadoRealizada()
    {
        return $this->estado === self::ESTADO_REALIZADA;
    }

    public function setEstadoToRealizada()
    {
        $this->estado = self::ESTADO_REALIZADA;
    }

}

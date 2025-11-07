<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "animal".
 *
 * @property int $id
 * @property string $nome
 * @property string $dtanascimento
 * @property float $peso
 * @property int $microship
 * @property string $sexo
 * @property int $especies_id
 * @property int $userprofiles_id
 * @property int|null $racas_id
 * @property int $eliminado
 *
 * @property Especie $especie
 * @property Marcacao[] $marcacao
 * @property Raca $raca
 * @property Userprofile $userprofile
 */
class Animal extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const SEXO_M = 'M';
    const SEXO_F = 'F';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'animais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['racas_id'], 'default', 'value' => null],
            [['eliminado'], 'default', 'value' => 0],
            [['nome', 'dtanascimento', 'peso', 'microship', 'sexo', 'especies_id', 'userprofiles_id'], 'required'],
            [['dtanascimento'], 'safe'],
            [['peso'], 'number'],
            [['microship', 'especies_id', 'userprofiles_id', 'racas_id', 'eliminado'], 'integer'],
            [['sexo'], 'string'],
            [['nome'], 'string', 'max' => 45],
            ['sexo', 'in', 'range' => array_keys(self::optsSexo())],
            [['especies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especie::class, 'targetAttribute' => ['especies_id' => 'id']],
            [['racas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Raca::class, 'targetAttribute' => ['racas_id' => 'id']],
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
            'nome' => 'Nome',
            'dtanascimento' => 'Dtanascimento',
            'peso' => 'Peso',
            'microship' => 'Microship',
            'sexo' => 'Sexo',
            'especies_id' => 'Especie ID',
            'userprofiles_id' => 'Userprofile ID',
            'racas_id' => 'Raca ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Especie]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEspecies()
    {
        return $this->hasOne(Especie::class, ['id' => 'especies_id']);
    }

    /**
     * Gets query for [[Marcacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasMany(Marcacao::class, ['animais_id' => 'id']);
    }

    /**
     * Gets query for [[Raca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRacas()
    {
        return $this->hasOne(Raca::class, ['id' => 'racas_id']);
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
     * column sexo ENUM value labels
     * @return string[]
     */
    public static function optsSexo()
    {
        return [
            self::SEXO_M => 'M',
            self::SEXO_F => 'F',
        ];
    }

    /**
     * @return string
     */
    public function displaySexo()
    {
        return self::optsSexo()[$this->sexo];
    }

    /**
     * @return bool
     */
    public function isSexoM()
    {
        return $this->sexo === self::SEXO_M;
    }

    public function setSexoToM()
    {
        $this->sexo = self::SEXO_M;
    }

    /**
     * @return bool
     */
    public function isSexoF()
    {
        return $this->sexo === self::SEXO_F;
    }

    public function setSexoToF()
    {
        $this->sexo = self::SEXO_F;
    }
}

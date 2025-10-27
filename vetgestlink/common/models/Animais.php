<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "animais".
 *
 * @property int $id
 * @property string $nome
 * @property string $datanascimento
 * @property string|null $notas
 * @property float $peso
 * @property int $microship
 * @property string $sexo
 * @property int $especies_id
 * @property int $userprofiles_id
 * @property int|null $racas_id
 * @property int $eliminado
 *
 * @property Especies $especies
 * @property Marcacoes[] $marcacoes
 * @property Racas $racas
 * @property Userprofiles $userprofiles
 */
class Animais extends \yii\db\ActiveRecord
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
            [['notas', 'racas_id'], 'default', 'value' => null],
            [['eliminado'], 'default', 'value' => 0],
            [['nome'], 'required', 'message' => 'O nome não pode estar vazio.'],
            [['datanascimento'], 'required', 'message' => 'A data de nascimento não pode estar vazia.'],
            [['peso'], 'required', 'message' => 'O peso não pode estar vazio.'],
            [['microship'], 'required', 'message' => 'Deve indicar se tem microchip.'],
            [['sexo'], 'required', 'message' => 'Deve selecionar o sexo.'],
            [['especies_id'], 'required', 'message' => 'Deve selecionar uma espécie.'],
            [['userprofiles_id'], 'required', 'message' => 'Deve selecionar um dono.'],
            [['datanascimento'], 'safe'],
            [['peso'], 'number', 'message' => 'O peso deve ser um número válido.'],
            [['microship', 'especies_id', 'userprofiles_id', 'racas_id', 'eliminado'], 'integer'],
            [['sexo'], 'string'],
            [['nome'], 'string', 'max' => 45, 'tooLong' => 'O nome não pode ter mais de 45 caracteres.'],
            [['notas'], 'string', 'max' => 500, 'tooLong' => 'As notas não podem ter mais de 500 caracteres.'],
            ['sexo', 'in', 'range' => array_keys(self::optsSexo()), 'message' => 'Sexo inválido.'],
            [['especies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especies::class, 'targetAttribute' => ['especies_id' => 'id']],
            [['racas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Racas::class, 'targetAttribute' => ['racas_id' => 'id']],
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
            'nome' => 'Nome',
            'datanascimento' => 'Datanascimento',
            'notas' => 'Notas',
            'peso' => 'Peso',
            'microship' => 'Microship',
            'sexo' => 'Sexo',
            'especies_id' => 'Especies ID',
            'userprofiles_id' => 'Userprofiles ID',
            'racas_id' => 'Racas ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Especies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEspecies()
    {
        return $this->hasOne(Especies::class, ['id' => 'especies_id']);
    }

    /**
     * Gets query for [[Marcacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasMany(Marcacoes::class, ['animais_id' => 'id']);
    }

    /**
     * Gets query for [[Racas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRacas()
    {
        return $this->hasOne(Racas::class, ['id' => 'racas_id']);
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

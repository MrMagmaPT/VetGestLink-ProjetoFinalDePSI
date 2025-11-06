<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "userprofiles".
 *
 * @property int $id
 * @property string $nomecompleto
 * @property string $nif
 * @property string $telemovel
 * @property string $dtanascimento
 * @property string $dtaregisto
 * @property int $user_id
 * @property int $eliminado
 *
 * @property Animal[] $animais
 * @property Fatura[] $faturas
 * @property Marcacao[] $marcacoes
 * @property Morada[] $moradas
 * @property Nota[] $notas
 * @property User $user
 */
class Userprofile extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'dtaregisto',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userprofiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['nomecompleto', 'nif', 'telemovel', 'dtanascimento', 'dtaregisto', 'user_id'], 'required'],
            [['dtanascimento', 'dtaregisto'], 'safe'],
            [['user_id', 'eliminado'], 'integer'],
            [['nomecompleto'], 'string', 'max' => 45],
            [['nif', 'telemovel'], 'string', 'max' => 9],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomecompleto' => 'Nomecompleto',
            'nif' => 'Nif',
            'telemovel' => 'Telemovel',
            'dtanascimento' => 'Dtanascimento',
            'dtaregisto' => 'Dtaregisto',
            'user_id' => 'User ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Animais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasMany(Animal::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Marcacos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasMany(Marcacao::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Moradas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoradas()
    {
        return $this->hasMany(Morada::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Notas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotas()
    {
        return $this->hasMany(Nota::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}

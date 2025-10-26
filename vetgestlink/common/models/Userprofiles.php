<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "userprofiles".
 *
 * @property int $id
 * @property string $nomecompleto
 * @property string $nif
 * @property string $telemovel
 * @property string $dtanascimento
 * @property int $user_id
 * @property int $eliminado
 * @property string $dtaregisto
 * @property string|null $userprofilescol
 *
 * @property Animais[] $animais
 * @property Faturas[] $faturas
 * @property Marcacoes[] $marcacoes
 * @property Moradas[] $moradas
 * @property User $user
 */
class Userprofiles extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dtaregisto'],
                ],
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
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
            [['userprofilescol'], 'default', 'value' => null],
            [['eliminado'], 'default', 'value' => 0],
            [['nomecompleto', 'nif', 'telemovel', 'dtanascimento', 'user_id'], 'required'],
            [['dtanascimento', 'dtaregisto'], 'safe'],
            [['user_id', 'eliminado'], 'integer'],
            [['nomecompleto', 'userprofilescol'], 'string', 'max' => 45],
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
            'user_id' => 'User ID',
            'eliminado' => 'Eliminado',
            'dtaregisto' => 'Dtaregisto',
            'userprofilescol' => 'Userprofilescol',
        ];
    }

    /**
     * Gets query for [[Animais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasMany(Animais::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Faturas::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Marcacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasMany(Marcacoes::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Moradas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoradas()
    {
        return $this->hasMany(Moradas::class, ['userprofiles_id' => 'id']);
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

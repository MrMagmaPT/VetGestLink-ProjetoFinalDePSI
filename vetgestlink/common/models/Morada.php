<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "moradas".
 *
 * @property int $id
 * @property string $rua
 * @property string $nporta
 * @property string|null $andar
 * @property string $cdpostal
 * @property string $cidade
 * @property string|null $cxpostal
 * @property string $localidade
 * @property int $principal
 * @property int $userprofiles_id
 * @property string $created_at
 * @property string $updated_at

 *
 * @property Userprofile $userprofile
 */
class Morada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moradas';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['andar', 'cxpostal'], 'default', 'value' => null],

            ['rua', 'required', 'message' => 'O campo Rua é obrigatório.'],
            ['nporta', 'required', 'message' => 'O campo Número da Porta é obrigatório.'],
            ['cdpostal', 'required', 'message' => 'O campo Código Postal é obrigatório.'],
            ['cidade', 'required', 'message' => 'O campo Cidade é obrigatório.'],
            ['localidade', 'required', 'message' => 'O campo Localidade é obrigatório.'],
            [['principal', 'userprofiles_id'], 'required'],

            [['principal', 'userprofiles_id'], 'integer'],
            ['nporta', 'integer', 'message' => 'O Número da Porta deve ser um número válido.'],
            ['andar', 'integer', 'message' => 'O Andar deve ser um número válido.'],

            ['cdpostal', 'match', 'pattern' => '/^\d{4}-\d{3}$/', 'message' => 'O Código Postal deve ter o formato 0000-000.'],

            [['rua', 'nporta', 'andar', 'cdpostal', 'cidade', 'cxpostal', 'localidade'], 'string', 'max' => 45],

            [['created_at', 'updated_at'], 'safe'],

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
            'rua' => 'Rua',
            'nporta' => 'Nporta',
            'andar' => 'Andar',
            'cdpostal' => 'Cdpostal',
            'cidade' => 'Cidade',
            'cxpostal' => 'Cxpostal',
            'localidade' => 'Localidade',
            'principal' => 'Principal',
            'userprofiles_id' => 'Userprofile ID',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * Gets query for [[Userprofile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }

}

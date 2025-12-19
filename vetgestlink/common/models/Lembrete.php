<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "lembretes".
 *
 * @property int $id
 * @property string $descricao
 * @property string $created_at
 * @property string $updated_at
 * @property int $userprofiles_id
 *
 * @property Userprofile $userprofile
 */
class Lembrete extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lembretes';
    }

    /**
     * {@inheritdoc}
     * //Coloca data e hora atual nos campos created_at e updated_at automaticamente
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao', 'userprofiles_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['userprofiles_id'], 'integer'],
            [['descricao'], 'string', 'max' => 255],
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
            'descricao' => 'Descrição',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
            'userprofiles_id' => 'Userprofile ID',
        ];
    }

    /**
     * Gets query for [[Userprofile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofile()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }

    
}

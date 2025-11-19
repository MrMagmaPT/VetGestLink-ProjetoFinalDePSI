<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notas".
 *
 * @property int $id
 * @property string $nota
 * @property string $created_at
 * @property int $userprofiles_id
 * @property int $animais_id
 *
 * @property Animal $animais
 * @property Userprofile $userprofiles
 */
class Nota extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nota', 'created_at', 'userprofiles_id', 'animais_id'], 'required'],
            [['created_at'], 'safe'],
            [['userprofiles_id', 'animais_id'], 'integer'],
            [['nota'], 'string', 'max' => 500],
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
            'nota' => 'Nota',
            'created_at' => 'Create At',
            'userprofiles_id' => 'Userprofiles ID',
            'animais_id' => 'Animais ID',
        ];
    }

    /**
     * Gets query for [[Animais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasOne(Animal::class, ['id' => 'animais_id']);
    }

    /**
     * Gets query for [[UserProfiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }

}

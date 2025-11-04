<?php

namespace common\models;

use Yii;

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
 * @property int $eliminado
 *
 * @property Userprofiles $userprofiles
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
    public function rules()
    {
        return [
            [['andar', 'cxpostal'], 'default', 'value' => null],
            [['eliminado'], 'default', 'value' => 0],
            [['rua', 'nporta', 'cdpostal', 'cidade', 'localidade', 'principal', 'userprofiles_id'], 'required'],
            [['principal', 'userprofiles_id', 'eliminado'], 'integer'],
            [['rua', 'nporta', 'andar', 'cdpostal', 'cidade', 'cxpostal', 'localidade'], 'string', 'max' => 45],
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
            'rua' => 'Rua',
            'nporta' => 'Nporta',
            'andar' => 'Andar',
            'cdpostal' => 'Cdpostal',
            'cidade' => 'Cidade',
            'cxpostal' => 'Cxpostal',
            'localidade' => 'Localidade',
            'principal' => 'Principal',
            'userprofiles_id' => 'Userprofiles ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Userprofiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }

}
